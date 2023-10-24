<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Day
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): array
    {
        $questionEntities = [];

        $select = $this->sql
            ->select('question')
            ->columns($this->questionTable->getSelectColumns())
            ->where([
                'deleted_datetime' => null,
            ])
            ->order('views_not_bot_one_day DESC')
            ->limit($limit)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            $questionEntities[] = $this->questionFactory->buildFromArray($array);
        }

        return $questionEntities;
    }
}
