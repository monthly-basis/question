<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class DeletedTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );

        $this->questionTable = new QuestionTable\Question(
            $this->sql
        );
        $this->questionDeletedTable = new QuestionTable\Question\Deleted(
            $this->getAdapter(),
            $this->memcachedServiceMock,
            $this->questionTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            QuestionTable\Question\Deleted::class,
            $this->questionDeletedTable
        );
    }
}
