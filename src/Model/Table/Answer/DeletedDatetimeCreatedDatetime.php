<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;

class DeletedDatetimeCreatedDatetime
{
    public function __construct(
        protected Adapter $adapter,
        protected QuestionTable\Answer $answerTable
    ) {
    }

    public function selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(): Generator {
        $sql = $this->answerTable->getSelect()
             . '
              FROM `answer`
             WHERE `answer`.`deleted_datetime` IS NULL
             ORDER
                BY `answer`.`created_datetime` DESC
             LIMIT 100
                 ;
        ';
        foreach ($this->adapter->query($sql)->execute() as $array) {
            yield $array;
        }
    }
}
