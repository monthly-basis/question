<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Answer;

use DateTime;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\LaminasTest\TableTestCase;

class CreatedIpTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);

        $this->answerTable = new QuestionTable\Answer(
            $this->getSql()
        );
        $this->createdIpTable = new QuestionTable\Answer\CreatedIp(
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

        $this->answerTable->insertDeleted(
            12345,
            null,
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

        $this->answerTable->insertDeleted(
            54321,
            null,
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

        $this->answerTable->insertDeleted(
            222,
            null,
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

        $this->answerTable->insertDeleted(
            128,
            null,
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
