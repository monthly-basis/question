<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Db as QuestionDb;

class Post
{
    protected QuestionDb\Sql $sql;

    public function __construct(
        QuestionDb\Sql $sql
    ) {
        $this->sql = $sql;
    }

    public function selectFromAnswerUnionQuestion(
        int $userId
    ): Result {
        $sql = "
            SELECT * FROM (

                (
                SELECT 'question' AS `entity_type`
                     , NULL AS `answer_id`
                     , `question_id`
                     , `user_id`
                     , `subject`
                     , `created_datetime`
                  FROM `question`

                 WHERE `user_id` = :userId
                   AND `deleted_datetime` IS NULL

                 ORDER
                    BY `created_datetime` DESC
                     , `question_id` DESC

                 LIMIT 0, 100
                )

            UNION ALL

                (
                SELECT 'answer' AS `entity_type`
                     , `answer`.`answer_id`
                     , `answer`.`question_id`
                     , `answer`.`user_id`
                     , NULL AS `subject`
                     , `answer`.`created_datetime`

                  FROM `answer`

                  JOIN `question`
                 USING (`question_id`)

                 WHERE `answer`.`user_id` = :userId
                   AND `answer`.`deleted_datetime` IS NULL
                   AND `question`.`deleted_datetime` IS NULL

                 ORDER
                    BY `answer`.`created_datetime` DESC
                     , `answer`.`answer_id` DESC

                 LIMIT 0, 100
                )

            ) AS `post` ORDER BY `created_datetime` DESC LIMIT 0, 100;
        ";
        $parameters = [
            'userId' => $userId,
        ];
        return $this->sql->getAdapter()->query($sql)->execute($parameters);
    }
}
