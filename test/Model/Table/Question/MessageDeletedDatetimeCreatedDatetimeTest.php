<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class MessageDeletedDatetimeCreatedDatetimeTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql
        );
        $this->messageDeletedDatetimeCreatedDatetimeTable = new QuestionTable\Question\MessageDeletedDatetimeCreatedDatetime(
            $this->getAdapter(),
            $this->questionTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);
    }

    public function test_selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1_emptyTable_emptyResult()
    {
        $result = $this->messageDeletedDatetimeCreatedDatetimeTable
            ->selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1(
                'this is the message'
            );
        $this->assertEmpty($result);
    }

    public function test_selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1_multipleRows_oneResult()
    {
        $this->questionTable->insertDeprecated(
            null,
            'this is the subject',
            'this is the message',
            'this is the name',
            '1.2.3.4'
        );
        $this->questionTable->insertDeprecated(
            null,
            'this is another subject',
            'this is the message',
            'this is another name',
            '5.6.7.8'
        );

        $result = $this->messageDeletedDatetimeCreatedDatetimeTable
            ->selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1(
                'this is the message'
            );
        $this->assertSame(
            [
                2,
                'this is another subject',
                'this is the message',
                'this is another name',
                '5.6.7.8',
            ],
            [
                $result->current()['question_id'],
                $result->current()['subject'],
                $result->current()['message'],
                $result->current()['created_name'],
                $result->current()['created_ip'],
            ]
        );
    }

    public function test_selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1_deletedRow_oneResult()
    {
        $this->questionTable->insertDeprecated(
            null,
            'this is the subject',
            'this is the message',
            'this is the name',
            '1.2.3.4'
        );
        $this->questionTable->insertDeleted(
            0,
            'this is another subject',
            'this is the message',
            'this is another name',
            '5.6.7.8',
            '99',
            'deleted reason'
        );

        $result = $this->messageDeletedDatetimeCreatedDatetimeTable
            ->selectWhereMessageAndDeletedDatetimeIsNullOrderByCreatedDatetimeDescLimit1(
                'this is the message'
            );
        $this->assertSame(
            [
                1,
                'this is the subject',
                'this is the message',
                'this is the name',
                '1.2.3.4',
            ],
            [
                $result->current()['question_id'],
                $result->current()['subject'],
                $result->current()['message'],
                $result->current()['created_name'],
                $result->current()['created_ip'],
            ]
        );
    }
}
