<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Db as MonthlyBasisDb;

class QuestionReport
{
    protected MonthlyBasisDb\Sql $sql;

    public function __construct(MonthlyBasisDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function getColumns(): array
    {
        return [
            'question_report_id',
            'question_id',
            'user_id',
            'reason',
            'report_status_id',
            'created_datetime',
            'modified_datetime',
        ];
    }

    public function insertIgnore(
        int $questionId,
        int $userId = null,
        string $reason,
        string $createdIp
    ): Result {
        $insertIgnore = (new \Laminas\Db\Sql\InsertIgnore())
            ->into('question_report')
            ->values([
                'question_id'  => $questionId,
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
            ->from('question_report')
            ->where([
                'reason'           => $reason,
                'report_status_id' => $reportStatusId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function selectWhereQuestionReportId(
        int $questionReportId
    ): Result {
        $select = $this->sql
            ->select()
            ->columns($this->getColumns())
            ->from('question_report')
            ->where([
                'question_report_id' => $questionReportId,
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
            ->from('question_report')
            ->where([
                'reason'           => $reason,
                'report_status_id' => $reportStatusId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }

    public function updateSetReportStatusIdWhereQuestionReportId(
        int $reportStatusId,
        int $questionReportId
    ): Result {
        $update = $this->sql
            ->update()
            ->table('question_report')
            ->set([
                'report_status_id' => $reportStatusId,
            ])
            ->where([
                'question_report_id' => $questionReportId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
