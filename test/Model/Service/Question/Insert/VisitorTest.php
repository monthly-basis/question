<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Insert;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class VisitorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->visitorService = new QuestionService\Question\Insert\Visitor(
            $this->questionFactoryMock,
            $this->questionTableMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_insert_questionEntity()
    {
        $_POST = [
            'message' => 'message',
            'name'    => 'name',
        ];
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];
        $resultMock = $this->createMock(Result::class);
        $resultMock
            ->expects($this->once())
            ->method('getGeneratedValue')
            ->willReturn(54321);

        $this->questionTableMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'message'    => 'message',
                'name'       => 'name',
                'created_ip' => '1.2.3.4',
            ])
            ->willReturn($resultMock)
            ;
        $questionEntity = new QuestionEntity\Question();
        $this->questionFactoryMock
             ->expects($this->once())
             ->method('buildFromQuestionId')
             ->with(54321)
             ->willReturn($questionEntity)
             ;

        $this->assertSame(
            $questionEntity,
            $this->visitorService->insert(),
        );
    }
}
