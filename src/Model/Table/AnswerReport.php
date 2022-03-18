<?php
namespace MonthlyBasis\Question\Model\Table;

use MonthlyBasis\Question\Model\Db as QuestionDb;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class AnswerReport
{
    protected QuestionDb\Sql $sql;

    public function __construct(QuestionDb\Sql $sql)
    {
        $this->sql     = $sql;
        $this->adapter = $sql->getAdapter();
    }

    public function getColumns(): array
    {
        return [
            'answer_report_id',
            'answer_id',
            'user_id',
            'reason',
            'created_datetime',
            'report_status_id',
            'modified',
        ];
    }

    public function insertIgnore(
        int $answerId,
        int $userId = null,
        string $reason,
        string $createdIp
    ): Result {
        $insertIgnore = (new \Laminas\Db\Sql\InsertIgnore())
            ->into('answer_report')
            ->values([
                'answer_id'  => $answerId,
                'user_id'    => $userId,
                'reason'     => $reason,
                'created_ip' => $createdIp,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($insertIgnore)->execute();
    }

    public function selectCountWhereReasonAndReportStatusId(
        string $reason,
        int $reportStatusId
    ): Result {
        $select = $this->sql
            ->select()
            ->columns([
                'COUNT(*)' => new \Laminas\Db\Sql\Expression('COUNT(*)')
            ])
            ->from('answer_report')
            ->where([
                'reason'           => $reason,
                'report_status_id' => $reportStatusId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function selectAnswerIdCountGroupByAnswerId(): Result
    {
        $sql = "
            SELECT `answer_report`.`answer_id`
                 , COUNT(*)
              FROM `answer_report`
             WHERE `answer_report`.`reason` != 'Honor Code Violation'
               AND `answer_report`.`report_status_id` = 0
             GROUP
                BY `answer_report`.`answer_id`
             ORDER
                BY `COUNT(*)` DESC
             LIMIT 100
                 ;
        ";

        return $this->adapter->query($sql)->execute();
    }

    public function selectWhereAnswerReportId(
        int $answerReportId
    ): Result {
        $select = $this->sql
            ->select()
            ->columns($this->getColumns())
            ->from('answer_report')
            ->where([
                'answer_report_id' => $answerReportId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function selectWhereReasonAndReportStatusId(
        string $reason,
        int $reportStatusId
    ): Result {
        $select = $this->sql
            ->select()
            ->columns($this->getColumns())
            ->from('answer_report')
            ->where([
                'reason'           => $reason,
                'report_status_id' => $reportStatusId,
            ])
            ->limit(100)
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function updateSetReportStatusIdWhereAnswerReportId(
        int $reportStatusId,
        int $answerReportId
    ): Result {
        $update = $this->sql
            ->update()
            ->table('answer_report')
            ->set([
                'report_status_id' => $reportStatusId,
            ])
            ->where([
                'answer_report_id' => $answerReportId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }

    public function updateSetReportStatusIdWhereAnswerIdAndReportStatusIdEquals0(
        int $reportStatusId,
        int $answerId
    ): Result {
        $update = $this->sql
            ->update()
            ->table('answer_report')
            ->set([
                'report_status_id' => $reportStatusId,
            ])
            ->where([
                'answer_id'        => $answerId,
                'report_status_id' => 0,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
