<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class AnswerEditQueueTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath;

    protected function setUp(): void
    {
        $this->answerEditQueueTable = new QuestionTable\AnswerEditQueue($this->getAdapter());

        $this->dropTable('answer_edit_queue');
        $this->createTable('answer_edit_queue');
    }

    public function testInsert()
    {
        $answerEditQueueId = $this->answerEditQueueTable->insert(
            12345,
            54321,
            1,
            'name',
            'message',
            'ip',
            'reason'
        );
        $this->assertSame(
            $answerEditQueueId,
            1
        );
        $answerEditQueueId = $this->answerEditQueueTable->insert(
            67890,
            9876,
            1,
            'name',
            'message',
            'ip',
            'reason'
        );
        $this->assertSame(
            $answerEditQueueId,
            2
        );
    }
}
