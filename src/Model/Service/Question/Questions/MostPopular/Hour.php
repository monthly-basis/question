<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Hour
{
    public function __construct(
        LaminasDb\Sql\Sql $sql,
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->sql             = $sql;
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function getQuestions(): Generator
    {
        $select = $this->sql
            ->select('question')
            ->columns($this->questionTable->getSelectColumns())
            ->where([
                'deleted_datetime' => null,
            ])
            ->order('views_not_bot_one_hour DESC')
            ->limit(100)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
