<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerDeleteQueueTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer_delete_queue');
        $this->setForeignKeyChecks1(1);

        $this->answerDeleteQueueTable = new QuestionTable\AnswerDeleteQueue(
            $this->getAdapter()
        );
    }

    public function testInsert()
    {
        $answerEditQueueId = $this->answerDeleteQueueTable->insert(
            12345,
            54321,
            'reason'
        );
        $this->assertSame(
            $answerEditQueueId,
            1
        );
        $answerEditQueueId = $this->answerDeleteQueueTable->insert(
            12345,
            54321,
            'reason'
        );
        $this->assertSame(
            $answerEditQueueId,
            2
        );
    }
}
