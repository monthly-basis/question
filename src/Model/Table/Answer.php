<?php
namespace MonthlyBasis\Question\Model\Table;

use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class Answer extends LaminasDb\Table
{
    public function __construct(
        \Laminas\Db\Sql\Sql $sql
    ) {
        $this
            ->setAdapter($sql->getAdapter())
            ->setSql($sql)
            ->setTable('answer')
        ;
    }

    public function getSelect(): string
    {
        return '
            SELECT `answer`.`answer_id`
                 , `answer`.`question_id`
                 , `answer`.`user_id`
                 , `answer`.`message`
                 , `answer`.`created_datetime`
                 , `answer`.`created_name`
                 , `answer`.`created_ip`
                 , `answer`.`modified_datetime`
                 , `answer`.`modified_user_id`
                 , `answer`.`modified_reason`
                 , `answer`.`deleted_datetime`
                 , `answer`.`deleted_user_id`
                 , `answer`.`deleted_reason`
        ';
    }

    public function insertDeprecated(
        int $questionId,
        int $userId = null,
        string $message,
        string $createdName = null,
        string $createdIp
    ): int {
        $sql = '
            INSERT
              INTO `answer` (
                       `question_id`
                     , `user_id`
                     , `message`
                     , `created_datetime`
                     , `created_name`
                     , `created_ip`
                   )
            VALUES (?, ?, ?, UTC_TIMESTAMP(), ?, ?)
                 ;
        ';
        $parameters = [
            $questionId,
            $userId,
            $message,
            $createdName,
            $createdIp,
        ];
        return (int) $this->getAdapter()
            ->query($sql)
            ->execute($parameters)
            ->getGeneratedValue();
    }

    public function insertDeleted(
        int $questionId,
        int $userId = null,
        string $message,
        string $createdName,
        string $createdIp,
        int $deletedUserId,
        string $deletedReason
    ): int {
        $sql = '
            INSERT
              INTO `answer` (
                       `question_id`
                     , `user_id`
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
            $questionId,
            $userId,
            $message,
            $createdName,
            $createdIp,
            $deletedUserId,
            $deletedReason,
        ];
        return (int) $this->getAdapter()
            ->query($sql)
            ->execute($parameters)
            ->getGeneratedValue();
    }

    public function selectCount(): int
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `answer`
                 ;
        ';
        $row = $this->getAdapter()->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    public function selectCountWhereQuestionId(int $questionId): int
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `answer`
             WHERE `question_id` = ?
                 ;
        ';
        $row = $this->getAdapter()->query($sql)->execute([$questionId])->current();
        return (int) $row['count'];
    }

    public function selectMaxCreatedDatetimeWhereQuestionId(
        int $questionId
    ): Result {
        $sql = '
            SELECT MAX(`answer`.`created_datetime`)
              FROM `answer`
             WHERE `answer`.`question_id` = ?
               AND `answer`.`deleted_datetime` IS NULL
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }

    /**
     * @deprecated Use QuestionTable\Answer\AnswerId::selectWhereAnswerId instead
     */
    public function selectWhereAnswerId(int $answerId) : array
    {
        $sql = $this->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $answerId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters)->current();
    }

    public function selectWhereQuestionId(int $questionId): Generator
    {
        $sql = $this->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`question_id` = :questionId
             ORDER
                BY `answer`.`created_datetime` ASC
                 ;
        ';
        $parameters = [
            'questionId' => $questionId,
        ];
        foreach ($this->getAdapter()->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }

    public function selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
        int $questionId
    ): Result {
        $sql = $this->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`question_id` = ?
               AND `answer`.`deleted_datetime` IS NULL
             ORDER
                BY `answer`.`created_datetime` ASC
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }

    /**
     * This method is currently not in use.
     * Before using it again, make sure it is performant.
     */
    public function selectWhereUserId(
        int $userId,
        int $limitOffset,
        int $limitRowCount
    ): Generator {
        $sql = $this->getSelect()
             . '
              FROM `answer`

              JOIN `question`
             USING (`question_id`)

             WHERE `answer`.`user_id` = ?
               AND `answer`.`deleted_datetime` IS NULL
               AND `question`.`deleted_datetime` IS NULL

             ORDER
                BY `question`.`views_one_month` DESC

             LIMIT ?, ?
                 ;
        ';
        $parameters = [
            $userId,
            $limitOffset,
            $limitRowCount,
        ];
        foreach ($this->getAdapter()->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }

    public function selectWhereUserIdOrderByCreatedDatetimeDesc(
        int $userId,
        int $limitOffset,
        int $limitRowCount
    ): Result {
        $sql = '
            SELECT `answer`.`answer_id`
              FROM `answer`

              JOIN `question`
             USING (`question_id`)

             WHERE `answer`.`user_id` = ?
               AND `answer`.`deleted_datetime` IS NULL
               AND `question`.`deleted_datetime` IS NULL

             ORDER
                BY `answer`.`created_datetime` DESC

             LIMIT ?, ?
                 ;
        ';
        $parameters = [
            $userId,
            $limitOffset,
            $limitRowCount,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }

    public function updateWhereAnswerId(
        string $name = null,
        string $message,
        int $modifiedUserId,
        string $modifiedReason,
        int $answerId
    ): Result {
        $sql = '
            UPDATE `answer`
               SET `answer`.`created_name` = ?
                 , `answer`.`message` = ?
                 , `answer`.`modified_datetime` = UTC_TIMESTAMP()
                 , `answer`.`modified_user_id` = ?
                 , `answer`.`modified_reason` = ?
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $name,
            $message,
            $modifiedUserId,
            $modifiedReason,
            $answerId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }
}
