<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Month
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): Generator
    {
        $result = $this->questionTable->select(
            columns: [
                'question_id'
            ],
            order: [
                'views_one_month DESC'
            ],
            limit: $limit
        );

        foreach ($result as $array) {
            $questionEntity = $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            yield $questionEntity;
        }
    }
}
