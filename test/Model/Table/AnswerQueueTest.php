<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerQueueTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('answer_queue');

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->answerQueueTable = new QuestionTable\AnswerQueue(
            $this->sql
        );
    }

    public function test_insert_columnsAndValues_result()
    {
        $result = $this->answerQueueTable->insert(
            columns: [
                'question_id',
                'name',
                'message',
            ],
            values: [
                '12345',
                'the name',
                'the message',
            ],
        );
        $result = $this->answerQueueTable->select(
            columns: [
                'answer_queue_id',
                'question_id',
                'name',
                'message',
            ],
            where: [
                'answer_queue_id' => 1,
            ],
        );
        $this->assertSame(
            [
                'answer_queue_id' => 1,
                'question_id'     => 12345,
                'name'            => 'the name',
                'message'         => 'the message',
            ],
            $result->current(),
        );
    }

    public function test_insert_values_result()
    {
        $result = $this->answerQueueTable->insert(
            values: [
                'question_id' => '12345',
                'name'        => 'the name',
                'message'     => 'the message',
            ],
        );
        $result = $this->answerQueueTable->select(
            columns: [
                'answer_queue_id',
                'question_id',
                'name',
                'message',
            ],
            where: [
                'answer_queue_id' => 1,
            ],
        );
        $this->assertSame(
            [
                'answer_queue_id' => 1,
                'question_id'     => 12345,
                'name'            => 'the name',
                'message'         => 'the message',
            ],
            $result->current(),
        );
    }

    public function test_select_whereQuestionId_result()
    {
        $this->answerQueueTable->insert(
            values: [
                'question_id' => '11111',
                'name'        => 'the first name',
                'message'     => 'the first message',
            ],
        );
        $this->answerQueueTable->insert(
            values: [
                'question_id' => '22222',
                'name'        => 'the second name',
                'message'     => 'the second message',
            ],
        );
        $this->answerQueueTable->insert(
            values: [
                'question_id' => '33333',
                'name'        => 'the third name',
                'message'     => 'the third message',
            ],
        );
        $result = $this->answerQueueTable->select(
            columns: [
                'answer_queue_id',
                'question_id',
                'name',
                'message',
            ],
            where: [
                'question_id' => 22222,
            ],
        );
        $this->assertSame(
            [
                'answer_queue_id' => 2,
                'question_id'     => 22222,
                'name'            => 'the second name',
                'message'         => 'the second message',
            ],
            $result->current(),
        );
    }
}
