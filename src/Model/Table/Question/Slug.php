<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Slug
{
    protected Adapter $adapter;
    protected QuestionTable\Question $questionTable;

    public function __construct(
        Adapter $adapter,
        QuestionTable\Question $questionTable
    ) {
        $this->adapter       = $adapter;
        $this->questionTable = $questionTable;
    }

    public function selectWhereSlug(string $slug): Result
    {
        $sql = $this->questionTable->getSelect()
            . '
              FROM `question`
             WHERE `question`.`slug` = ?
                 ;
        ';
        $parameters = [
            $slug,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
