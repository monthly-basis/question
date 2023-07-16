<?php
namespace MonthlyBasis\Question\Model\Table;

use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Question\Model\Db as QuestionDb;

class CategoryQuestion extends LaminasDb\Table
{
    protected string $table = 'category_question';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql
    ) {
    }
}
