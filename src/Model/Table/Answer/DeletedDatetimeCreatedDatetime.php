<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class DeletedDatetimeCreatedDatetime
{
    public function __construct(
        protected Adapter $adapter,
        protected QuestionTable\Answer $answerTable
    ) {
    }

    public function selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(
        int $limitRowCount = 100
    ): Result {
        $sql = $this->answerTable->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`deleted_datetime` IS NULL
             ORDER
                BY `answer`.`created_datetime` DESC
             LIMIT ?
                 ;
        ';
        $parameters = [
            $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
