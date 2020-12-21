<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;

class DeletedDatetimeCreatedDatetime
{
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Question $questionTable
    ) {
        $this->adapter       = $adapter;
        $this->questionTable = $questionTable;
    }

    public function selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc(
        int $limitOffset,
        int $limitRowCount
    ): Generator {
        $sql = $this->questionTable->getSelect()
             . '
              FROM `question`
             WHERE `question`.`deleted_datetime` IS NULL
             ORDER
                BY `question`.`created_datetime` DESC
             LIMIT ?, ?
                 ;
        ';
        $parameters = [
            $limitOffset,
            $limitRowCount,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }
}
