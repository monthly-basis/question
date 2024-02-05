<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Year
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable,
    ) {
    }

    public function getQuestions(
        int $year
    ): Generator {
        $betweenMin = "$year-01-01 00:00:00";
        $betweenMax = "$year-12-31 23:59:59";

        $select = $this->sql
            ->select('question')
            ->columns(['question_id'])
            ->where([
                new LaminasDb\Sql\Predicate\Between('created_datetime', $betweenMin, $betweenMax),
                'deleted_datetime' => null,
            ])
            ->order('views_one_month DESC')
            ->limit(100)
            ->offset(0)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId($array['question_id']);
        }
    }
}
