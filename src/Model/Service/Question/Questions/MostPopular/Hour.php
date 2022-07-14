<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Hour
{
    public function __construct(
        QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->fromQuestionIdFactory = $fromQuestionIdFactory;
        $this->questionTable         = $questionTable;
    }

    public function getQuestions(): Generator
    {
        $result = $this->questionTable->selectQuestionIdOrderByViewsNotBotOneHour();

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
