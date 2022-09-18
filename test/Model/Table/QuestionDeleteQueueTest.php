<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class QuestionDeleteQueueTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question_delete_queue');
        $this->setForeignKeyChecks(1);

        $this->questionDeleteQueueTable = new QuestionTable\QuestionDeleteQueue(
            $this->getAdapter()
        );
    }

    public function testInsert()
    {
        $answerEditQueueId = $this->questionDeleteQueueTable->insert(
            12345,
            54321,
            'reason'
        );
        $this->assertSame(
            $answerEditQueueId,
            1
        );
        $answerEditQueueId = $this->questionDeleteQueueTable->insert(
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
