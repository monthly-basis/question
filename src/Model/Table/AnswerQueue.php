<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Db as QuestionDb;

class AnswerQueue
{
    protected string $table = 'answer_queue';

    public function __construct(
        protected QuestionDb\Sql $sql
    ) {
    }

    public function insert(
        array $values,
        array $columns = null,
    ): Result {
        $insert = $this->sql->insert()->into($this->table);

        if (isset($columns)) {
            $insert->columns($columns);
        }

        $insert->values($values);

        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }

    public function select(
        array $columns = null,
        array $where = null,
    ): Result {
        $select = $this->sql->select($this->table);

        if (isset($columns)) {
            $select->columns($columns);
        }

        if (isset($where)) {
            $select->where($where);
        }

        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }
}
