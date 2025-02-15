<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Related
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\QuestionsRelated $questionsRelatedTable
    ) {
    }

    public function getRelatedQuestions(
        QuestionEntity\Question $questionEntity,
        int $limit = 4,
    ): Generator {
        $questionIds = [];

        $result = $this->questionsRelatedTable->select(
            where: [
                'question_id' => $questionEntity->questionId,
            ],
            order: 'score DESC',
            limit: $limit,
        );
        foreach ($result as $array) {
            $questionEntity = $this->questionFactory->buildFromQuestionId(
                intval($array['related_question_id'])
            );

            if (isset($questionEntity->deletedDateTime)) {
                continue;
            }

            yield $questionEntity;
        }
    }
}
