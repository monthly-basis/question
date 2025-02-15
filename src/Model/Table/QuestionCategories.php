<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class QuestionCategories extends LaminasDb\Table
{
    public function __construct(
        Sql $sql
    ) {
        $this
            ->setAdapter($sql->getAdapter())
            ->setSql($sql)
            ->setTable('answer')
        ;
    }

    public function selectSubCategoryIds(
        int $categoryId,
    ): Result {
        $sql = '
            SELECT `category_id_2`
                 , COUNT(*) as `count`
              FROM `question_categories`
             WHERE `category_id_1` = ?
               AND `category_id_2` IS NOT NULL
             GROUP
                BY `category_id_2`
             ORDER
                BY `count` DESC
                 , `category_id_2` ASC
             LIMIT 10
                 ;
        ';
        $parameters = [
            $categoryId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }

    public function selectWhereQuestionId(
        int $questionId,
    ): Result {
        $sql = '
            SELECT `id`
                 , `question_id`
                 , `category_id_1`
                 , `category_id_2`
                 , `category_id_3`
              FROM `question_categories`
             WHERE `question_id` = ?
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }
}
