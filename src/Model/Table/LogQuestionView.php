<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class LogQuestionView extends LaminasDb\Table
{
    protected string $table = 'log_question_view';

    public function __construct(
        protected Sql $sql
    ) {
    }
}
