<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class CreatedName
{
    public function __construct(
        protected LaminasDb\Sql\Sql $sql,
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getMostPopularQuestions(
        string $createdName,
        int $page
    ): Generator {
        $select = $this->sql
            ->select('question')
            ->columns(['question_id'])
            ->where([
                'created_name'     => $createdName,
                'deleted_datetime' => null,
            ])
            ->order('views_one_month DESC')
            ->limit(100)
            ->offset(($page - 1) * 100)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
