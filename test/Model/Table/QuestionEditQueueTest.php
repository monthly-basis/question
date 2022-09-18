<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class QuestionEditQueueTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question_edit_queue');
        $this->setForeignKeyChecks(1);

        $this->questionEditQueueTable = new QuestionTable\QuestionEditQueue(
            $this->getAdapter()
        );
    }

    public function testInsert()
    {
        $questionEditQueueId = $this->questionEditQueueTable->insert(
            12345,
            1,
            'name',
            'subject',
            'message',
            'ip',
            'reason'
        );
        $this->assertSame(
            $questionEditQueueId,
            1
        );
        $questionEditQueueId = $this->questionEditQueueTable->insert(
            67890,
            1,
            'name',
            'subject',
            'message',
            'ip',
            'reason'
        );
        $this->assertSame(
            $questionEditQueueId,
            2
        );
    }
}
