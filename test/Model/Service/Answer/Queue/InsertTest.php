<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer\Queue;

use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerQueueTableMock = $this->createMock(
            QuestionTable\AnswerQueue::class
        );
        $this->insertService = new QuestionService\Answer\Queue\Insert(
            $this->answerQueueTableMock,
        );
    }

    public function test_insert_allFields_void()
    {
        $this->answerQueueTableMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id'   => 12345,
                'message'       => 'the message',
                'created_ip'    => '1.2.3.4',
                'name'          => 'name',
                'createdUserId' => 1,
            ])
        ;

        $this->insertService->insert(
            12345,
            'the message',
            '1.2.3.4',
            'name',
            1
        );
    }
}
