<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class AnswerReportTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('answer_report');

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->answerTable = new QuestionTable\Answer(
            $this->getAdapter()
        );
        $this->answerReportTable = new QuestionTable\AnswerReport(
            $this->sql
        );
    }

    public function test_insertIgnore()
    {
        $this->answerTable->insert(
            12345,
            null,
            'message',
            'created name',
            'created IP',
        );
        $result = $this->answerReportTable->insertIgnore(
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

    public function test_selectAnswerIdCountGroupByAnswerId()
    {
        $this->answerTable->insert(
            123,
            null,
            'message',
            'created name',
            'created IP',
        );
        $this->answerTable->insert(
            456,
            null,
            'message',
            'created name',
            'created IP 2',
        );
        $result = $this->answerReportTable->insertIgnore(
            2,
            null,
            'reason',
            '1.2.3.4',
        );
        $result = $this->answerReportTable->insertIgnore(
            2,
            null,
            'reason',
            '5.6.7.8',
        );
        $result = $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '5.6.7.8',
        );
        $result = $this->answerReportTable->insertIgnore(
            1,
            null,
            'Honor Code Violation',
            '5.6.7.8',
        );
        $result = $this->answerReportTable->selectAnswerIdCountGroupByAnswerId();
        $array  = iterator_to_array($result);
        $this->assertSame(
            [
                [
                    'answer_id' => '2',
                    'COUNT(*)'  => '2',
                ],
                [
                    'answer_id' => '1',
                    'COUNT(*)'  => '1',
                ]
            ],
            $array
        );
    }

    public function test_updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0()
    {
        $result = $this->answerReportTable->updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
            5,
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->answerTable->insert(
            123,
            null,
            'message',
            'created name',
            'created IP',
        );
        $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '1.2.3.4',
        );
        $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '5.6.7.8',
        );
        $result = $this->answerReportTable->updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
            5,
            1,
        );
        $this->assertSame(
            2,
            $result->getAffectedRows()
        );
        $result = $this->answerReportTable->updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );
        $result = $this->answerReportTable->selectWhereAnswerReportId(1);
        $this->assertSame(
            '5',
            $result->current()['report_status_id'],
        );

        $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '9.10.11.12',
        );
        $result = $this->answerReportTable->updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
