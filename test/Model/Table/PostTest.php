<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use Laminas\Db\Adapter\Adapter;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use PHPUnit\Framework\TestCase;

class PostTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->postTable = new QuestionTable\Post(
            $this->sql
        );

        $this->answerTable = new QuestionTable\Answer(
            $this->sql
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['answer', 'question']);
        $this->setForeignKeyChecks(1);
    }

    public function test_selectFromAnswerUnionQuestionOrderByCreatedDatetimeDesc()
    {
        $this->markTestSkipped(
            'Skip test for now until table model returns both questions and answers.',
        );

        $result = $this->postTable->selectFromAnswerUnionQuestionOrderByCreatedDatetimeDesc(123);
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

        $result = $this->postTable->selectFromAnswerUnionQuestionOrderByCreatedDatetimeDesc(123);
        $this->assertCount(
            3,
            $result
        );
        $array = $result->current();
        unset($array['created_datetime']);
        $this->assertSame(
            [
                'entity_type' => 'question',
                'answer_id'   => null,
                'question_id' => '2',
                'user_id'     => '123',
                'subject'     => 'subject for question 2',
                'message'     => 'message for question 2',
            ],
            $array,
        );
    }
}
