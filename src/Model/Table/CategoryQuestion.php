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

    public function selectCountWhereCategoryId(
        int $categoryId,
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `category_question`
             WHERE `category_question`.`category_id` = ?
                 ;
        ';
        $parameters = [
            $categoryId,
        ];
        return $this->sql->getAdapter()->query($sql)->execute($parameters);
    }
}
