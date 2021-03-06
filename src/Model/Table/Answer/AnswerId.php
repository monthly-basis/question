<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerId
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Answer $answerTable
    ) {
        $this->adapter     = $adapter;
        $this->answerTable = $answerTable;
    }

    public function selectWhereAnswerId(int $answerId): Result
    {
        $sql = $this->answerTable->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function updateSetDeletedColumnsWhereAnswerId(
        int $deletedUserId,
        string $deletedReason,
        int $answerId
    ): int {
        $sql = '
            UPDATE `answer`
               SET `answer`.`deleted_datetime` = UTC_TIMESTAMP()
                 , `answer`.`deleted_user_id` = ?
                 , `answer`.`deleted_reason` = ?
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $deletedUserId,
            $deletedReason,
            $answerId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }

    public function updateSetDeletedColumnsToNullWhereAnswerId(
        int $answerId
    ): int {
        $sql = '
            UPDATE `answer`
               SET `answer`.`deleted_datetime` = NULL
                 , `answer`.`deleted_user_id` = NULL
                 , `answer`.`deleted_reason` = NULL
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return (int) $this->adapter
                          ->query($sql)
                          ->execute($parameters)
                          ->getAffectedRows();
    }

    public function updateSetCreatedNameWhereAnswerId(
        string $createdName,
        int $answerId
    ): int {
        $sql = '
            UPDATE `answer`
               SET `answer`.`created_name` = ?
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $createdName,
            $answerId,
        ];
        return (int) $this->adapter
            ->query($sql)
            ->execute($parameters)
            ->getAffectedRows();
    }

    public function updateSetModifiedReasonWhereAnswerId(
        string $modifiedReason,
        int $answerId
    ): Result {
        $sql = '
            UPDATE `answer`
               SET `answer`.`modified_reason` = ?
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $modifiedReason,
            $answerId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
