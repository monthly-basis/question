<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use DateTime;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class CreatedDatetimeTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->answerTable = new QuestionTable\Answer(
            $this->getAdapter()
        );

        $this->createdDatetimeTable = new QuestionTable\Answer\CreatedDatetime(
            $this->sql
        );
    }

    public function test_selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals()
    {
        $result = $this->createdDatetimeTable->selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
            new DateTime(),
            '1.2.3.4',
            'message',
        );
        $this->assertSame(
            $result->current(),
            [
                'COUNT(*)' => 0,
            ],
        );

        $this->answerTable->insert(
            12345,
            null,
            'the message',
            'created name',
            '1.2.3.4',
        );
        $this->answerTable->insert(
            12345,
            null,
            'the message',
            'created name',
            '1.2.3.4',
        );

        $result = $this->createdDatetimeTable->selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
            (new DateTime())->modify('-5 minutes'),
            '1.2.3.4',
            'the message',
        );
        $this->assertSame(
            $result->current(),
            [
                'COUNT(*)' => 2,
            ],
        );

        $result = $this->createdDatetimeTable->selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
            (new DateTime())->modify('-5 minutes'),
            'a different IP',
            'the message',
        );
        $this->assertSame(
            $result->current(),
            [
                'COUNT(*)' => 0,
            ],
        );

        $result = $this->createdDatetimeTable->selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
            (new DateTime())->modify('-5 minutes'),
            '1.2.3.4',
            'a different message',
        );
        $this->assertSame(
            $result->current(),
            [
                'COUNT(*)' => 0,
            ],
        );
    }
}
