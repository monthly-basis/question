<?php
namespace MonthlyBasis\Question\Model\Table;

use Generator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use TypeError;

class Question
{
    protected Adapter $adapter;
    protected Sql $sql;

    public function __construct(
        QuestionDb\Sql $sql
    ) {
        $this->sql     = $sql;
        $this->adapter = $this->sql->getAdapter();
    }

    public function getSelect(): string
    {
        return '
            SELECT `question`.`question_id`
                 , `question`.`user_id`
                 , `question`.`subject`
                 , `question`.`headline`
                 , `question`.`message`
                 , `question`.`views`
                 , `question`.`views_not_bot_one_month`
                 , `question`.`created_datetime`
                 , `question`.`created_name`
                 , `question`.`created_ip`
                 , `question`.`modified_user_id`
                 , `question`.`modified_datetime`
                 , `question`.`modified_reason`
                 , `question`.`moved_datetime`
                 , `question`.`moved_user_id`
                 , `question`.`moved_language`
                 , `question`.`moved_question_id`
                 , `question`.`deleted_datetime`
                 , `question`.`deleted_user_id`
                 , `question`.`deleted_reason`
        ';
    }

    /**
     * Get columns which are commonly-used for SELECT statements.
     */
    public function getSelectColumns(): array
    {
        return [
            'question_id',
            'user_id',
            'subject',
            'message',
            'views',
            'views_not_bot_one_month',
            'created_datetime',
            'created_name',
            'created_ip',
            'modified_user_id',
            'modified_datetime',
            'modified_reason',
            'deleted_datetime',
            'deleted_user_id',
            'deleted_reason',
        ];
    }


    public function insert(
        int $userId = null,
        string $subject,
        string $message = null,
        string $createdName = null,
        string $createdIp
    ): int {
        $sql = '
            INSERT
              INTO `question` (
                       `user_id`
                     , `subject`
                     , `message`
                     , `created_datetime`
                     , `created_name`
                     , `created_ip`
                   )
            VALUES (?, ?, ?, UTC_TIMESTAMP(), ?, ?)
                 ;
        ';
        $parameters = [
            $userId,
            $subject,
            $message,
            $createdName,
            $createdIp,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
                    ->getGeneratedValue();
    }

    public function insertDeleted(
        int $userId = null,
        string $subject,
        string $message,
        string $createdName,
        string $createdIp,
        string $deletedUserId,
        string $deletedReason
    ): int {
        $sql = '
            INSERT
              INTO `question` (
                       `user_id`
                     , `subject`
                     , `message`
                     , `created_datetime`
                     , `created_name`
                     , `created_ip`
                     , `deleted_datetime`
                     , `deleted_user_id`
                     , `deleted_reason`
                   )
            VALUES (?, ?, ?, UTC_TIMESTAMP(), ?, ?, UTC_TIMESTAMP(), ?, ?)
                 ;
        ';
        $parameters = [
            $userId,
            $subject,
            $message,
            $createdName,
            $createdIp,
            $deletedUserId,
            $deletedReason,
        ];
        return (int) $this->adapter
            ->query($sql)
            ->execute($parameters)
            ->getGeneratedValue();
    }

    public function selectQuestionIdOrderByViewsNotBotOneHour(): Result {
        $sql = '
            SELECT `question_id`
              FROM (
                       SELECT `question`.`question_id`
                         FROM `question`
                        WHERE `question`.`deleted_datetime` IS NULL
                        ORDER
                           BY `question`.`views_not_bot_one_hour` DESC
                        LIMIT 100
                   ) AS `question_sub_query`

              JOIN `question` USING (`question_id`)

             ORDER
                BY `question`.`views_not_bot_one_hour` DESC
                 , `question`.`views_not_bot_one_day` DESC
                 , `question`.`views_not_bot_one_week` DESC
                 , `question`.`views_not_bot_one_month` DESC
                 , `question`.`views` DESC
                 ;
        ';
        return $this->adapter->query($sql)->execute();
    }

    public function selectWhereDeletedDatetimeIsNullOrderByCreatedDateTimeDesc(
        int $limitOffset,
        int $limitRowCount
    ): Generator {
        $sql = $this->getSelect()
             . "
              FROM `question`
             WHERE `deleted_datetime` IS NULL
             ORDER
                BY `question`.`created_datetime` DESC
             LIMIT $limitOffset, $limitRowCount
                 ;
        ";
        foreach ($this->adapter->query($sql)->execute() as $array) {
            yield($array);
        }
    }

    /**
     * @deprecated Use QuestionTable\Question\QuestionId::selectWhereQuestionId instead
     *
     * Select where question ID.
     *
     * @param int $questionId
     * @return array
     * @throws TypeError
     */
    public function selectWhereQuestionId(int $questionId): array
    {
        $sql = $this->getSelect()
             . '
              FROM `question`
             WHERE `question`.`question_id` = ?
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->adapter->query($sql)->execute($parameters)->current();
    }

    public function selectWhereQuestionIdInAndDeletedDatetimeIsNull(
        array $questionIds
    ): Generator {
        $questionIds = array_map('intval', $questionIds);
        $questionIds = implode(', ', $questionIds);

        $sql = $this->getSelect()
             . "
              FROM `question`
             WHERE `question`.`question_id` IN ($questionIds)
               AND `question`.`deleted_datetime` IS NULL
             ORDER
                BY FIELD(`question`.`question_id`, $questionIds)

                 ;
        ";
        foreach ($this->adapter->query($sql)->execute() as $array) {
            yield $array;
        }
    }

    public function updateViewsWhereQuestionId(int $questionId) : bool
    {
        $sql = '
            UPDATE `question`
               SET `question`.`views` = `question`.`views` + 1
             WHERE `question`.`question_id` = :questionId
                 ;
        ';
        $parameters = [
            'questionId' => $questionId,
        ];
        return (bool) $this->adapter->query($sql, $parameters)->getAffectedRows();
    }

    public function updateWhereQuestionId(
        string $name = null,
        string $subject,
        string $message,
        int $modifiedUserId,
        string $modifiedReason,
        int $questionId
    ): Result {
        $sql = '
            UPDATE `question`
               SET `question`.`created_name` = ?
                 , `question`.`subject` = ?
                 , `question`.`message` = ?
                 , `question`.`modified_datetime` = UTC_TIMESTAMP()
                 , `question`.`modified_user_id` = ?
                 , `question`.`modified_reason` = ?
             WHERE `question`.`question_id` = ?
                 ;
        ';
        $parameters = [
            $name,
            $subject,
            $message,
            $modifiedUserId,
            $modifiedReason,
            $questionId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
