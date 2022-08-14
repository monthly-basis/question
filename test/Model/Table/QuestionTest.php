<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql
        );
        $this->questionIdTable = new QuestionTable\Question\QuestionId(
            $this->getAdapter(),
            $this->questionTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);
    }

    public function test_getSelectColumns()
    {
        $this->assertIsArray(
            $this->questionTable->getSelectColumns()
        );
    }

    public function testInsertDeleted()
    {
        $questionId = $this->questionTable->insertDeleted(
            null,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            0,
            'foul language'
        );
        $this->assertSame(
            1,
            $questionId
        );

        $array = $this->questionTable->selectWhereQuestionId(1);
        $this->assertSame(
            0,
            $array['deleted_user_id']
        );
        $this->assertSame(
            'foul language',
            $array['deleted_reason']
        );
    }

    /**
     * @todo Update unit test to use actual data.
     */
    public function test_selectQuestionIdOrderByViewsNotBotOneHour_result()
    {
        $result = $this->questionTable->selectQuestionIdOrderByViewsNotBotOneHour();
        $this->assertEmpty($result);
    }

    public function testSelectWhereQuestionId()
    {
        $this->questionTable->insert(
            3,
            'this is the subject',
            'message',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );
        $array = $this->questionTable->selectWhereQuestionId(1);
        $this->assertIsArray(
            $array
        );
        $this->assertSame(
            1,
            $array['question_id']
        );
        $this->assertSame(
            3,
            $array['user_id']
        );
        $this->assertSame(
            'this is the subject',
            $array['subject']
        );
    }

    public function testSelectWhereQuestionIdInAndDeletedDatetimeIsNull()
    {
        $this->questionTable->insert(
            1, 'name', 'subject', 'message', '1.2.3.4', 'name', '1.2.3.4'
        );
        $this->questionTable->insert(
            2, 'name', 'subject', 'message', '5.6.7.8', 'name', '5.6.7.8'
        );
        $generator = $this->questionTable->selectWhereQuestionIdInAndDeletedDatetimeIsNull(
            [1, 2, 3, 'string', 'injection' => 'attempt']
        );
        $this->assertInstanceOf(
            Generator::class,
            $generator
        );
        $array = iterator_to_array($generator);
        $this->assertSame(
            2,
            count($array)
        );
    }

    public function test_updateWhereQuestionId()
    {
        $this->questionTable->insert(
            1, 'name', 'subject', 'message', '1.2.3.4', 'name', '1.2.3.4'
        );

        $result = $this->questionTable->updateWhereQuestionId(
            'new name',
            'modified subject',
            'modified message',
            10,
            'modified reason',
            1
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->questionIdTable->selectWhereQuestionId(1);
        $this->assertSame(
            'new name',
            $result->current()['created_name']
        );
        $this->assertSame(
            'modified subject',
            $result->current()['subject']
        );
        $this->assertSame(
            'modified message',
            $result->current()['message']
        );
        $this->assertSame(
            'modified reason',
            $result->current()['modified_reason']
        );

        $result = $this->questionTable->updateWhereQuestionId(
            null,
            'modified subject',
            'modified message',
            10,
            'modified reason',
            1
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->questionIdTable->selectWhereQuestionId(1);
        $this->assertNull(
            $result->current()['created_name']
        );
    }
}
