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

        $this->conditionallyInsertService = new QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert(
            $this->questionViewNotBotLogTableGatewayMock,
            $this->botServiceMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isBot_false()
    {
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];

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

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsNotSet_false()
    {
        $_SERVER = [
            'REMOTE_ADDR'  => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsNotGoogle_false()
    {
        $_SERVER = [
            'HTTP_REFERER' => 'https://www.bing.com/search?q=hello+world',
            'REMOTE_ADDR'  => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsGoogleInvalidQueryExceptionThrown_false()
    {
        $_SERVER = [
            'HTTP_REFERER' => 'google.com',
            'REMOTE_ADDR'  => '1.2.3.4',
        ];

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
                'question_id'         => 12345,
                'ip'                  => '1.2.3.4',
                'server_http_referer' => 'google.com',
            ])
            ->will($this->throwException(new InvalidQueryException()))
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsGoogleNoExceptionThrown_true()
    {
        $_SERVER = [
            'HTTP_REFERER' => 'https://www.google.com/search?q=hello+world&extra=add+really+long+query+string+to+make+sure+url+is+truncated+to+only+first+255+characters+since+the+varchar+in+mysql+is+only+256+characters+in+length+otherwise+the+script+may+fail+i+hope+you+didnt+read+this+far',
            'REMOTE_ADDR'  => '1.2.3.4',
        ];

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
                'question_id'         => 12345,
                'ip'                  => '1.2.3.4',
                'server_http_referer' => 'https://www.google.com/search?q=hello+world&extra=add+really+long+query+string+to+make+sure+url+is+truncated+to+only+first+255+characters+since+the+varchar+in+mysql+is+only+256+characters+in+length+otherwise+the+script+may+fail+i+hope+you+didnt+read+this+',
            ])
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertTrue($result);
    }
}
