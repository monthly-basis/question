<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Unanswered
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable,
    ) {}

    public function getUnansweredQuestions(): Generator
    {
        $result = $this->questionTable->select(
            columns: $this->questionTable->getSelectColumns(),
            where: [
                'answer_count_cached' => 0,
                'moved_datetime'      => null,
                'deleted_datetime'    => null,
            ],
            order: 'views_not_bot_one_month DESC',
            limit: 100,
        );
        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
