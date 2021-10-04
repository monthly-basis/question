<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class PostTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->postTable = new QuestionTable\Post(
            new QuestionDb\Sql($this->getAdapter())
        );

        $this->answerTable = new QuestionTable\Answer(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->getAdapter()
        );

        $this->dropAndCreateTables(['answer', 'question']);
    }

    public function test_selectFromAnswerUnionQuestion()
    {
        $result = $this->postTable->selectFromAnswerUnionQuestion(123);
        $this->assertEmpty($result);

        $this->questionTable->insert(
            123,
            'subject for question 1',
            'message for question 1',
            null,
            '255.255.255.255',
        );
        $this->answerTable->insert(
            1,
            123,
            'message for answer 1',
            null,
            '255.255.255.255'
        );
        $this->questionTable->insert(
            123,
            'subject for question 2',
            'message for question 2',
            null,
            '255.255.255.255',
        );
        $this->answerTable->insert(
            1,
            456,
            'message for answer 2',
            null,
            '255.255.255.255'
        );
        $this->answerTable->insertDeleted(
            1,
            123,
            'message',
            'name',
            '1.2.3.4',
            '0',
            'foul language'
        );

        $result = $this->postTable->selectFromAnswerUnionQuestion(123);
        $this->assertCount(
            3,
            $result
        );
        $array = $result->current();
        $this->assertSame(
            [
                'entity_type' => 'question',
                'answer_id'   => null,
                'question_id' => '2',
                'user_id'     => '123',
            ],
            [
                'entity_type' => $array['entity_type'],
                'answer_id'   => $array['answer_id'],
                'question_id' => $array['question_id'],
                'user_id'     => $array['user_id'],
            ]
        );
    }
}
