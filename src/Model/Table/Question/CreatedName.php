<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class CreatedName
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Question $questionTable
    ) {
        $this->adapter       = $adapter;
        $this->questionTable = $questionTable;
    }

    public function selectCountWhereCreatedName(
        string $createdName
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `question`
             WHERE `question`.`created_name` = ?
               AND `question`.`deleted_datetime` IS NULL
                 ;
        ';
        $parameters = [
            $createdName,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    /**
     * @return Generator
     * @yield array
     */
    public function selectWhereCreatedName(
        string $createdName,
        int $limitOffset,
        int $limitRowCount
    ): Generator {
        $sql = $this->questionTable->getSelect()
             . "
              FROM `question`
             WHERE `question`.`created_name` = ?
               AND `question`.`deleted_datetime` IS NULL
             ORDER
                BY `question`.`created_datetime` DESC
             LIMIT $limitOffset, $limitRowCount
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
