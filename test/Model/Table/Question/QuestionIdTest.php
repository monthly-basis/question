<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionIdTest extends TableTestCase
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

    public function test_selectWhereQuestionId()
    {
        $this->questionTable->insertDeprecated(
            3,
            'this is the subject',
            'message',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );
        $array = $this->questionIdTable->selectWhereQuestionId(1)->current();
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

    public function test_updateAnswerCountCachedWhereQuestionId()
    {
        $result = $this->questionIdTable->updateAnswerCountCachedWhereQuestionId(
            1
        );

        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->questionTable->insertDeprecated(
            null,
            'name',
            'subject',
            'message',
            'ip',
            'name',
            'ip'
        );
        $result = $this->questionIdTable->updateAnswerCountCachedWhereQuestionId(
            1
        );

        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->questionIdTable->updateAnswerCountCachedWhereQuestionId(
            1
        );
        $select = $this->sql
            ->select()
            ->columns([
                'answer_count_cached'
            ])
            ->from('question')
            ->where([
                'question_id' => 1,
            ])
            ;
        $array = $this->sql->prepareStatementForSqlObject($select)->execute()->current();
        $this->assertSame(
            2,
            $array['answer_count_cached']
        );
    }

    public function testUpdateSetDeletedColumnsWhereQuestionId()
    {
        $rowsAffected = $this->questionIdTable->updateSetDeletedColumnsWhereQuestionId(
            3,
            'deleted reason',
            1
        );
        $this->assertSame(
            0,
            $rowsAffected
        );

        $this->questionTable->insertDeprecated(
            null,
            'name',
            'subject',
            'message',
            'ip',
            'name',
            'ip'
        );

        $rowsAffected = $this->questionIdTable->updateSetDeletedColumnsWhereQuestionId(
            3,
            'deleted reason',
            1
        );
        $this->assertSame(
            1,
            $rowsAffected
        );
        $array = $this->questionTable->selectWhereQuestionId(1);
        $this->assertNotNull(
            $array['deleted_datetime']
        );
        $this->assertSame(
            3,
            $array['deleted_user_id']
        );
        $this->assertSame(
            'deleted reason',
            $array['deleted_reason']
        );
    }

    public function test_updateSetModifiedReasonWhereQuestionId_emptyTable_0AffectedRows()
    {
        $result = $this->questionIdTable
            ->updateSetModifiedReasonWhereQuestionId(
                'modified reason',
                12345
            );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );
    }

    public function test_updateSetModifiedReasonWhereQuestionId_multipleRows_1AffectedRow()
    {
        $this->questionTable->insertDeprecated(
            null,
            'name',
            'subject',
            'message',
            'ip'
        );
        $this->questionTable->insertDeprecated(
            null,
            'name 2',
            'subject 2',
            'message 2',
            'ip 2'
        );

        $result = $this->questionIdTable
            ->updateSetModifiedReasonWhereQuestionId(
                'a modified reason',
                2
            );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $array = $this->questionTable->selectWhereQuestionId(2);
        $this->assertSame(
            'a modified reason',
            $array['modified_reason']
        );
    }
}
