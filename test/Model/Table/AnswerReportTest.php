<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerReportTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['answer','answer_report', 'question']);
        $this->setForeignKeyChecks(1);

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->answerTable = new QuestionTable\Answer(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql
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
                    'answer_id' => 2,
                    'COUNT(*)'  => 2,
                ],
                [
                    'answer_id' => 1,
                    'COUNT(*)'  => 1,
                ]
            ],
            $array
        );
    }

    public function test_updateWhereAnswerIdAndReportStatusIdEquals0()
    {
        $result = $this->answerReportTable->updateWhereAnswerIdAndReportStatusIdEquals0(
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

        $result = $this->answerReportTable->updateWhereAnswerIdAndReportStatusIdEquals0(
            5,
            1,
        );
        $this->assertSame(
            2,
            $result->getAffectedRows()
        );
        $result = $this->answerReportTable->updateWhereAnswerIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $result = $this->answerReportTable->selectWhereAnswerReportId(1);
        $this->assertSame(
            5,
            $result->current()['report_status_id'],
        );

        $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '9.10.11.12',
        );
        $result = $this->answerReportTable->updateWhereAnswerIdAndReportStatusIdEquals0(
            -1,
            1,
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }

    public function test_updateWhereQuestionIdAndReportStatusIdEquals0()
    {
        $result = $this->answerReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            -4,
            2,
        );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $this->questionTable->insert(
            null,
            'subject for question 1',
            'message for question 1',
            'created name',
            'created ip',
        );
        $this->questionTable->insert(
            null,
            'subject for question 2',
            'message for question 2',
            'created name',
            'created ip',
        );
        $this->answerTable->insert(
            1,
            null,
            'message: answer to question 1',
            'created name',
            'created IP',
        );
        $this->answerTable->insert(
            2,
            null,
            'message: answer to question 2',
            'created name',
            'created IP',
        );

        $this->answerReportTable->insertIgnore(
            1,
            null,
            'reason',
            '1.1.1.1',
        );
        $this->answerReportTable->insertIgnore(
            2,
            null,
            'reason',
            '2.2.2.2',
        );
        $this->answerReportTable->insertIgnore(
            2,
            null,
            'reason',
            '3.3.3.3',
        );
        $this->answerReportTable->insertIgnore(
            2,
            null,
            'reason',
            '4.4.4.4',
        );
        $this->answerReportTable->updateSetReportStatusIdWhereAnswerReportId(
            1,
            3,
        );

        $result = $this->answerReportTable->updateWhereQuestionIdAndReportStatusIdEquals0(
            -4,
            2,
        );
        $this->assertSame(
            2,
            $result->getAffectedRows(),
        );

        $result = $this->answerReportTable->selectWhereAnswerReportId(1);
        $this->assertSame(
            0,
            $result->current()['report_status_id']
        );
        $result = $this->answerReportTable->selectWhereAnswerReportId(2);
        $this->assertSame(
            -4,
            $result->current()['report_status_id']
        );
        $result = $this->answerReportTable->selectWhereAnswerReportId(3);
        $this->assertSame(
            1,
            $result->current()['report_status_id']
        );
        $result = $this->answerReportTable->selectWhereAnswerReportId(4);
        $this->assertSame(
            -4,
            $result->current()['report_status_id']
        );
    }
}
