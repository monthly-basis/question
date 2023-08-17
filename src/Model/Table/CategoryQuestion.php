<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Question\Model\Db as QuestionDb;

class CategoryQuestion extends LaminasDb\Table
{
    protected string $table = 'category_question';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql
    ) {
    }

    public function selectQuestionIdWhereCategoryId(
        int $categoryId,
        int $limitOffset = 0,
        int $limitRowCount = 100,
    ): Result {
        $sql = '
            SELECT `question_id`
              FROM `category_question`
              JOIN `question`
             USING (`question_id`)

             WHERE `category_question`.`category_id` = ?
               AND `question`.`deleted_datetime` IS NULL
               AND `question`.`moved_datetime` IS NULL
             ORDER
                BY `question`.`views_not_bot_one_hour` DESC
                 , `question`.`views_not_bot_one_day` DESC
                 , `question`.`views_not_bot_one_week` DESC
                 , `question`.`views_not_bot_one_month` DESC
             LIMIT ?, ?
                 ;
        ';
        $parameters = [
            $categoryId,
            $limitOffset,
            $limitRowCount,
        ];
        return $this->sql->getAdapter()->query($sql)->execute($parameters);
    }

    public function selectCountWhereCategoryId(
        int $categoryId,
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `category_question`
              JOIN `question`
             USING (`question_id`)

             WHERE `category_question`.`category_id` = ?
               AND `question`.`deleted_datetime` IS NULL
               AND `question`.`moved_datetime` IS NULL
                 ;
        ';
        $parameters = [
            $categoryId,
        ];
        return $this->sql->getAdapter()->query($sql)->execute($parameters);
    }
}
