<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Month
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): Generator
    {
        $select = $this->sql
            ->select('question')
            ->columns([
                'question_id'
            ])
            ->where([
                'deleted_datetime' => null,
            ])
            ->order('views_not_bot_one_month DESC')
            ->limit($limit)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
