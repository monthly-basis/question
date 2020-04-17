<?php
namespace LeoGalleguillos\Question\Model\Table;

use Exception;
use Generator;
use Zend\Db\Adapter\Adapter;

class Answer
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

    public function insert(
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
        return (int) $this->adapter
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
        return (int) $this->adapter
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
        $row = $this->adapter->query($sql)->execute()->current();
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
        $row = $this->adapter->query($sql)->execute([$questionId])->current();
        return (int) $row['count'];
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
        return $this->adapter->query($sql)->execute($parameters)->current();
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
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }

    public function selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
        int $questionId
    ): Generator {

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
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }

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
                BY `question`.`views_browser` DESC

             LIMIT ?, ?
                 ;
        ';
        $parameters = [
            $userId,
            $limitOffset,
            $limitRowCount,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }

    public function updateWhereAnswerId(
        string $message,
        int $modifiedUserId,
        int $answerId
    ): int {
        $sql = '
            UPDATE `answer`
               SET `answer`.`message` = ?
                 , `answer`.`modified_datetime` = UTC_TIMESTAMP()
                 , `answer`.`modified_user_id` = ?
             WHERE `answer`.`answer_id` = ?
                 ;
        ';
        $parameters = [
            $message,
            $modifiedUserId,
            $answerId,
        ];
        return (int) $this->adapter
            ->query($sql)
            ->execute($parameters)
            ->getAffectedRows();
    }
}
