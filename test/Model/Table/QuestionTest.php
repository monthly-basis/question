<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);

        $this->questionTable = new QuestionTable\Question(
            $this->getSql()
        );
        $this->questionIdTable = new QuestionTable\Question\QuestionId(
            $this->getAdapter(),
            $this->questionTable
        );
    }

    public function test_getSelectColumns()
    {
        $this->assertIsArray(
            $this->questionTable->getSelectColumns()
        );
    }

    public function test_insert()
    {
        $result = $this->questionTable->insert(
            values: [
                'headline' => 'this is the headline',
            ],
        );
        $this->assertSame(
            1,
            $result->getAffectedRows(),
        );
        $this->assertSame(
            '1',
            $result->getGeneratedValue(),
        );
    }

    public function test_insertDeprecated()
    {
        $generatedValue = $this->questionTable->insertDeprecated(
            1,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            'headline',
            'slug',
        );
        $this->assertSame(
            1,
            $generatedValue
        );

        try {
            $this->questionTable->insertDeprecated(
                1,
                'subject',
                'message',
                'name',
                '1.2.3.4',
                'headline',
                'slug',
            );
            $this->fail();
        } catch (InvalidQueryException $invalidQueryException) {
            $this->assertSame(
                'Statement could not be executed (23000 - 1062 - Duplicate entry \'slug\' for key \'question.slug\')',
                $invalidQueryException->getMessage(),
            );
        }

        $generatedValue = $this->questionTable->insertDeprecated(
            1,
            null,
            'message for question with no headline and no slug',
            'name',
            '1.2.3.4',
        );
        $this->assertSame(
            3,
            $generatedValue
        );
    }

    public function test_insertDeleted()
    {
        $questionId = $this->questionTable->insertDeleted(
            null,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            0,
            'foul language',
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

        $questionId = $this->questionTable->insertDeleted(
            null,
            null,
            'message',
            null,
            '1.2.3.4',
            0,
            'insert deleted with null subject and null name',
        );
        $this->assertSame(
            2,
            $questionId
        );
    }

    public function test_select()
    {
        $this->questionTable->insert(
            values: [
                'headline' => 'the headline',
            ],
        );
        $result = $this->questionTable->select(
            where: [
                'moved_datetime' => null,
                'deleted_datetime' => null,
            ],
        );
        $this->assertSame(
            [
                'question_id'      => 1,
                'headline'         => 'the headline',
                'moved_datetime'   => null,
                'deleted_datetime' => null,
            ],
            [
                'question_id'      => $result->current()['question_id'],
                'headline'         => $result->current()['headline'],
                'moved_datetime'   => $result->current()['moved_datetime'],
                'deleted_datetime' => $result->current()['deleted_datetime'],
            ],
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
        $this->questionTable->insertDeprecated(
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
        $this->questionTable->insertDeprecated(
            1, 'name', 'subject', 'message', '1.2.3.4',
        );
        $this->questionTable->insertDeprecated(
            2, 'name', 'subject', 'message', '5.6.7.8',
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

    public function test_selectWhereUserIdOrderByCreatedDatetimeDesc()
    {
        $generator = $this->questionTable->selectWhereUserIdOrderByCreatedDatetimeDesc(1, 0, 100);
        $this->assertEmpty(
            iterator_to_array($generator)
        );
    }

    public function test_updateWhereQuestionId()
    {
        $this->questionTable->insertDeprecated(
            1, 'name', 'subject', 'message', '1.2.3.4',
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
