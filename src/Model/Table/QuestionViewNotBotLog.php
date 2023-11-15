<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class QuestionViewNotBotLog extends LaminasDb\Table
{
    protected string $table = 'question_view_not_bot_log';

    public function __construct(
        protected Sql $sql
    ) {
    }
}
