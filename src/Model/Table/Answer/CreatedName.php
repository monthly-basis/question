<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;

class CreatedName
{
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Answer $answerTable
    ) {
        $this->adapter     = $adapter;
        $this->answerTable = $answerTable;
    }

    public function selectCountWhereCreatedName(
        string $createdName
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `answer`
             WHERE `answer`.`created_name` = ?
               AND `answer`.`deleted_datetime` IS NULL
                 ;
        ';
        $parameters = [
            $createdName,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectWhereCreatedName(
        string $createdName,
        int $limitRowCount
    ): Generator {
        $sql = $this->answerTable->getSelect()
             . "
              FROM `answer`
             WHERE `answer`.`created_name` = ?
               AND `answer`.`deleted_datetime` IS NULL
             ORDER
                BY `answer`.`created_datetime` DESC
             LIMIT $limitRowCount
                 ;
        ";
        $parameters = [
            $createdName,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array;
        }
    }
}
