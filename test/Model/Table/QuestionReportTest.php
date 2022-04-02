<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class QuestionReportTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('question_report');

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->getAdapter()
        );
        $this->questionReportTable = new QuestionTable\QuestionReport(
            $this->sql
        );
    }

    public function test_insertIgnore()
    {
        $this->questionTable->insert(
            null,
            'subject',
            'message',
            'created name',
            'created ip',
        );
        $result = $this->questionReportTable->insertIgnore(
            1,
            null,
            'reason',
            '1.2.3.4',
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $this->assertSame(
            '1',
            $result->getGeneratedValue()
        );
    }
}
