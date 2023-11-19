<?php
namespace MonthlyBasis\Question\Model\Table;

use Generator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use TypeError;

class Question extends LaminasDb\Table
{
    protected Adapter $adapter;
    protected string $table = 'question';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql
    ) {
        $this->sql     = $sql;
        $this->adapter = $this->sql->getAdapter();
    }

    public function getSelect(): string
    {
        return '
            SELECT `question`.`question_id`
                 , `question`.`slug`
                 , `question`.`user_id`
                 , `question`.`subject`
                 , `question`.`headline`
                 , `question`.`message`
                 , `question`.`image_rru_128x128_webp`
                 , `question`.`image_rru_512x512_webp`
                 , `question`.`image_rru_1024x1024_jpeg`
                 , `question`.`image_rru_1024x1024_png`
                 , `question`.`did_you_know`
                 , `question`.`views`
                 , `question`.`views_one_year`
                 , `question`.`answer_count_cached`
                 , `question`.`created_datetime`
                 , `question`.`created_name`
                 , `question`.`created_ip`
                 , `question`.`modified_user_id`
                 , `question`.`modified_datetime`
                 , `question`.`modified_reason`
                 , `question`.`moved_datetime`
                 , `question`.`moved_user_id`
                 , `question`.`moved_country`
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
            'slug',
            'user_id',
            'subject',
            'headline',
            'message',
            'views',
            'answer_count_cached',
            'created_datetime',
            'created_name',
            'created_ip',
            'modified_user_id',
            'modified_datetime',
            'modified_reason',
            'moved_datetime',
            'moved_user_id',
            'moved_country',
            'moved_language',
            'moved_question_id',
            'deleted_datetime',
            'deleted_user_id',
            'deleted_reason',
        ];
    }


    public function insertDeprecated(
        ?int $userId,
        ?string $subject,
        ?string $message,
        ?string $createdName,
        string $createdIp,
        string $headline = null,
        string $slug = null,
    ): int {
        $sql = '
            INSERT
              INTO `question` (
                       `slug`
                     , `user_id`
                     , `subject`
                     , `headline`
                     , `message`
                     , `created_datetime`
                     , `created_name`
                     , `created_ip`
                   )
            VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP(), ?, ?)
                 ;
        ';
        $parameters = [
            $slug,
            $userId,
            $subject,
            $headline,
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
        string|null $subject,
        string $message,
        string|null $createdName,
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

    public function selectQuestionIdOrderByViewsOneHour(
        int $limitRowCount = 100
    ): Result {
        $sql = '
            SELECT `question_id`
              FROM (
                       SELECT `question`.`question_id`
                         FROM `question`
                        WHERE `question`.`deleted_datetime` IS NULL
                        ORDER
                           BY `question`.`views_one_hour` DESC
                        LIMIT ?
                   ) AS `question_sub_query`

              JOIN `question` USING (`question_id`)

             ORDER
                BY `question`.`views_one_hour` DESC
                 , `question`.`views_one_day` DESC
                 , `question`.`views_one_week` DESC
                 , `question`.`views_one_month` DESC
                 ;
        ';
        $parameters = [
            $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectQuestionIdOrderByViewsOneYearDesc(
        int $limitRowCount = 100
    ): Result {
        $sql = '
            SELECT `question_id`
              FROM `question`
             WHERE `views_one_year` > 0
               AND `deleted_datetime` IS NULL
             ORDER
                BY `views_one_year` DESC
             LIMIT ?
                 ;
        ';
        $parameters = [
            $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
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

    public function selectWhereUserIdOrderByCreatedDatetimeDesc(
        int $userId,
        int $limitOffset,
        int $limitRowCount
    ): Result {
        $sql = $this->getSelect()
             . '
              FROM `question`

             WHERE `question`.`user_id` = ?
               AND `question`.`deleted_datetime` IS NULL

             ORDER
                BY `question`.`created_datetime` DESC

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
        string|null $name,
        string|null $subject,
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
