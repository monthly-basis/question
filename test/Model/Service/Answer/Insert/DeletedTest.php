<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class DeletedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );

        $this->deletedService = new QuestionService\Answer\Insert\Deleted(
            $this->answerFactoryMock,
            $this->answerTableMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_insert()
    {
        $_POST = [
            'message'     => 'message',
            'name'        => 'name',
            'question-id' => '12345',
        ];
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];

        $this->answerTableMock
            ->expects($this->once())
            ->method('insertDeleted')
            ->with(
                '12345',
                null,
                'message',
                'name',
                '1.2.3.4',
                0,
                'foul language'
            )
            ->willReturn(54321)
            ;
        $answerEntity = new QuestionEntity\Answer();
        $this->answerFactoryMock
             ->expects($this->once())
             ->method('buildFromAnswerId')
             ->with(54321)
             ->willReturn($answerEntity)
             ;

        $this->assertSame(
            $answerEntity,
            $this->deletedService->insert(),
        );
    }
}
