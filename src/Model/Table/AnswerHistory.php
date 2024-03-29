<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class AnswerHistory
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getSelect(): string
    {
        return '
            SELECT `answer_history`.`answer_history_id`
                 , `answer_history`.`answer_id`
                 , `answer_history`.`name`
                 , `answer_history`.`message`
                 , `answer_history`.`modified_reason`
                 , `answer_history`.`created`
        ';
    }

    public function insertSelectFromAnswer(
        int $answerId
    ): int {
        $sql = '
            INSERT
              INTO `answer_history`
                 (
                      `answer_id`
                    , `name`
                    , `message`
                    , `modified_user_id`
                    , `modified_reason`
                    , `created`
                 )
            SELECT `answer`.`answer_id`
                 , `answer`.`created_name`
                 , `answer`.`message`
                 , `answer`.`modified_user_id`
                 , `answer`.`modified_reason`
                 , UTC_TIMESTAMP()
              FROM `answer`
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return (int) $this->adapter
            ->query($sql)
            ->execute($parameters)
            ->getGeneratedValue();
    }

    public function selectDistinctAnswerId(): Result
    {
        $sql = '
            SELECT
          DISTINCT `answer_id`
              FROM `answer_history`
             ORDER
                BY `answer_id` ASC
                 ;
        ';
        return $this->adapter->query($sql)->execute();
    }

    public function selectWhereAnswerIdOrderByCreatedAsc(
        int $answerId
    ): Result {
        $sql = $this->getSelect()
            . '
              FROM `answer_history`
             WHERE `answer_history`.`answer_id` = ?
             ORDER
                BY `answer_history`.`created` ASC
                 , `answer_history`.`answer_id` ASC
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectWhereAnswerIdOrderByCreatedDesc(
        int $answerId
    ): Result {
        $sql = $this->getSelect()
            . '
              FROM `answer_history`
             WHERE `answer_history`.`answer_id` = ?
             ORDER
                BY `answer_history`.`created` DESC
                 , `answer_history`.`answer_id` DESC
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function updateSetCreatedWhereAnswerHistoryId(
        string $created,
        int $answerHistoryId
    ): Result {
        $sql = '
            UPDATE `answer_history`
               SET `created` = ?
             WHERE `answer_history_id` = ?
                 ;
        ';
        $parameters = [
            $created,
            $answerHistoryId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function updateSetModifiedReasonWhereAnswerHistoryId(
        string $modifiedReason = null,
        int $answerHistoryId
    ): Result {
        $sql = '
            UPDATE `answer_history`
               SET `modified_reason` = ?
             WHERE `answer_history_id` = ?
                 ;
        ';
        $parameters = [
            $modifiedReason,
            $answerHistoryId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
