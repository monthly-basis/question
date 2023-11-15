<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Week
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(): Generator
    {
        $select = $this->sql
            ->select('question')
            ->columns($this->questionTable->getSelectColumns())
            ->where([
                'deleted_datetime' => null,
            ])
            ->order('views_one_week DESC')
            ->limit(100)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
