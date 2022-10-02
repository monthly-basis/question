<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Answer;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerIdTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->answerTable = new QuestionTable\Answer(
            $this->getAdapter()
        );
        $this->answerIdTable = new QuestionTable\Answer\AnswerId(
            $this->getAdapter(),
            $this->answerTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);
    }

    public function test_selectWhereAnswerId()
    {
        $this->answerTable->insertDeprecated(
            12345,
            null,
            'message',
            'name',
            'ip'
        );
        $array = $this->answerIdTable->selectWhereAnswerId(1)->current();
        $this->assertSame(
            1,
            $array['answer_id']
        );
        $this->assertSame(
            12345,
            $array['question_id']
        );
        $this->assertSame(
            'name',
            $array['created_name']
        );
        $this->assertSame(
            'ip',
            $array['created_ip']
        );
    }

    public function testUpdateSetDeletedColumnsWhereAnswerId()
    {
        $rowsAffected = $this->answerIdTable->updateSetDeletedColumnsWhereAnswerId(
            2,
            'deleted reason',
            1
        );
        $this->assertSame(
            0,
            $rowsAffected
        );

        $this->answerTable->insertDeprecated(
            12345,
            null,
            'name',
            'subject',
            'ip',
            'name',
            'ip'
        );

        $rowsAffected = $this->answerIdTable->updateSetDeletedColumnsWhereAnswerId(
            4,
            'deleted reason',
            1
        );
        $this->assertSame(
            1,
            $rowsAffected
        );
        $array = $this->answerTable->selectWhereAnswerId(1);
        $this->assertNotNull(
            $array['deleted_datetime']
        );
        $this->assertSame(
            4,
            $array['deleted_user_id']
        );
        $this->assertSame(
            'deleted reason',
            $array['deleted_reason']
        );
    }

    public function test_updateSetModifiedReasonWhereAnswerId_emptyTable_0AffectedRows()
    {
        $result = $this->answerIdTable
            ->updateSetModifiedReasonWhereAnswerId(
                'modified reason',
                12345
            );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );
    }

    public function test_updateSetModifiedReasonWhereAnswerId_multipleRows_1AffectedRow()
    {
        $this->answerTable->insertDeprecated(
            12345,
            null,
            'message',
            'name',
            'ip'
        );
        $this->answerTable->insertDeprecated(
            98765,
            null,
            'message 2',
            'name 2',
            'ip 2'
        );

        $result = $this->answerIdTable
            ->updateSetModifiedReasonWhereAnswerId(
                'a modified reason',
                1
            );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $array = $this->answerTable->selectWhereAnswerId(1);
        $this->assertSame(
            'a modified reason',
            $array['modified_reason']
        );
    }
}
