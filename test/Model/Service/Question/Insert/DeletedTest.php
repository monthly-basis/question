<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class DeletedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->deletedService = new QuestionService\Question\Insert\Deleted(
            $this->questionFactoryMock,
            $this->questionTableMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_insert_defaultReason_questionEntity()
    {
        $_POST = [
            'subject'     => 'subject',
            'message'     => 'message',
            'name'        => 'name',
        ];
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];

        $this->questionTableMock
            ->expects($this->once())
            ->method('insertDeleted')
            ->with(
                null,
                'subject',
                'message',
                'name',
                '1.2.3.4',
                0,
                'foul language'
            )
            ->willReturn(54321)
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
            $this->deletedService->insert(),
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_insert_customReason_questionEntity()
    {
        $_POST = [
            'subject'     => 'subject',
            'message'     => 'message',
            'name'        => 'name',
        ];
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];

        $this->questionTableMock
            ->expects($this->once())
            ->method('insertDeleted')
            ->with(
                null,
                'subject',
                'message',
                'name',
                '1.2.3.4',
                0,
                'custom reason'
            )
            ->willReturn(54321)
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
            $this->deletedService->insert('custom reason'),
        );
    }
}
