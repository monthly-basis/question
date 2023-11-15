<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Unanswered
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable,
    ) {}

    public function getUnansweredQuestions(): Generator
    {
        $result = $this->questionTable->select(
            columns: ['question_id'],
            where: [
                'answer_count_cached' => 0,
                'moved_datetime'      => null,
                'deleted_datetime'    => null,
            ],
            order: 'views_one_month DESC',
            limit: 100,
        );
        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionId(
                $array['question_id']
            );
        }
    }
}
