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
            $this->sql
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

    public function test_selectQuestionIdCountGroupByQuestionId()
    {
        $this->questionTable->insert(
            null,
            'subject 1',
            'message 1',
            'created name 1',
            'created ip 1',
        );
        $this->questionTable->insert(
            null,
            'subject 2',
            'message 2',
            'created name 2',
            'created ip 2',
        );
        $result = $this->questionReportTable->insertIgnore(
            2,
            null,
            'reason',
            '1.2.3.4',
        );
        $result = $this->questionReportTable->insertIgnore(
            2,
            null,
            'reason',
            '5.6.7.8',
        );
        $result = $this->questionReportTable->insertIgnore(
            1,
            null,
            'Honor Code Violation',
            '1.2.3.4',
        );
        $result = $this->questionReportTable->insertIgnore(
            1,
            null,
            'reason',
            '5.6.7.8',
        );
        $result = $this->questionReportTable->selectQuestionIdCountGroupByQuestionId();
        $array  = iterator_to_array($result);
        $this->assertSame(
            [
                [
                    'question_id' => '2',
                    'COUNT(*)'  => '2',
                ],
            ],
            $array
        );
    }

    public function test_updateWhereQuestionIdAndReportStatusIdEquals0()
    {
        $result = $this->questionReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            5,
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->questionTable->insert(
            null,
            'subject',
            'message',
            'created name',
            'created IP',
        );
        $this->questionReportTable->insertIgnore(
            1,
            null,
            'reason',
            '1.2.3.4',
        );
        $this->questionReportTable->insertIgnore(
            1,
            null,
            'reason',
            '5.6.7.8',
        );

        $result = $this->questionReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            5,
            1,
        );
        $this->assertSame(
            2,
            $result->getAffectedRows()
        );
        $result = $this->questionReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $result = $this->questionReportTable->selectWhereQuestionReportId(1);
        $this->assertSame(
            '5',
            $result->current()['report_status_id'],
        );

        $this->questionReportTable->insertIgnore(
            1,
            null,
            'reason',
            '9.10.11.12',
        );
        $result = $this->questionReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
