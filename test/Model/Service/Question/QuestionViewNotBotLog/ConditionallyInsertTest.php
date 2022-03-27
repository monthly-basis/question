<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\QuestionViewNotBotLog;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;
use PHPUnit\Framework\TestCase;

class ConditionallyInsertTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionViewNotBotLogTableGatewayMock = $this->createMock(
            TableGateway::class
        );
        $this->botServiceMock = $this->createMock(
            SuperglobalService\Server\HttpUserAgent\Bot::class
        );

        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';

        $this->conditionallyInsertService = new QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert(
            $this->questionViewNotBotLogTableGatewayMock,
            $this->botServiceMock
        );
    }

    public function test_conditionallyInsert_isBot_false()
    {
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(true)
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;
        $result = $this->conditionallyInsertService->conditionallyInsert(
            (new QuestionEntity\Question())
        );
        $this->assertFalse($result);
    }

    public function test_conditionallyInsert_invalidQueryExceptionThrown_false()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id' => 12345,
                'ip' => '1.2.3.4',
            ])
            ->will($this->throwException(new InvalidQueryException()))
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    public function test_conditionallyInsert_isNotBotAndNoExceptionThrown_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id' => 12345,
                'ip' => '1.2.3.4',
            ])
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertTrue($result);
    }
}
