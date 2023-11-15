<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Hour
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): Generator
    {
        $result = $this->questionTable->selectQuestionIdOrderByViewsOneHour(
            limitRowCount: $limit
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
