<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use DateTime;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class CreatedIpTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql,
        );
        $this->createdIpTable = new QuestionTable\Question\CreatedIp(
            $this->getAdapter(),
        );
    }

    public function test_selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason()
    {
        $result = $this->createdIpTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            '1.2.3.4',
            (new DateTime())->modify('-5 minutes'),
            0,
            'foul language',
        );
        $this->assertSame(
            [
                'COUNT(*)' => 0,
            ],
            $result->current(),
        );

        $this->questionTable->insertDeleted(
            null,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            0,
            'foul language',
        );
        $result = $this->createdIpTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            '1.2.3.4',
            (new DateTime())->modify('-5 minutes'),
            0,
            'foul language',
        );
        $this->assertSame(
            [
                'COUNT(*)' => 1,
            ],
            $result->current(),
        );

        $this->questionTable->insertDeleted(
            null,
            'subject',
            'message',
            'name',
            '255.255.255.255',
            32,
            'a different reason',
        );
        $result = $this->createdIpTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            '1.2.3.4',
            (new DateTime())->modify('-5 minutes'),
            0,
            'foul language',
        );
        $this->assertSame(
            [
                'COUNT(*)' => 1,
            ],
            $result->current(),
        );

        $this->questionTable->insertDeleted(
            null,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            0,
            'a different reason',
        );
        $result = $this->createdIpTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            '1.2.3.4',
            (new DateTime())->modify('-5 minutes'),
            0,
            'foul language',
        );
        $this->assertSame(
            [
                'COUNT(*)' => 1,
            ],
            $result->current(),
        );

        $this->questionTable->insertDeleted(
            null,
            'subject 2',
            'message 2',
            'name',
            '1.2.3.4',
            0,
            'foul language',
        );
        $result = $this->createdIpTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            '1.2.3.4',
            (new DateTime())->modify('-5 minutes'),
            0,
            'foul language',
        );
        $this->assertSame(
            [
                'COUNT(*)' => 2,
            ],
            $result->current(),
        );
    }
}
