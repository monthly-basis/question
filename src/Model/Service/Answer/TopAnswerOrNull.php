<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class TopAnswerOrNull
{
    public function __construct(
        protected QuestionService\Answer\Answers $answersService,
    ) {
    }

    public function getTopAnswerOrNull(
        QuestionEntity\Question $questionEntity,
    ): QuestionEntity\Answer|null {
        $answerEntities = $this->answersService->getAnswers(
            questionEntity: $questionEntity,
            withVotes: true,
        );

        if (empty($answerEntities)) {
            return null;
        }

        usort(
            $answerEntities,
            function ($answerEntity1, $answerEntity2)
            {
                if ($answerEntity1->rating != $answerEntity2->rating) {
                    return $answerEntity1->rating < $answerEntity2->rating;
                }

                return $answerEntity1->createdDateTime > $answerEntity2->createdDateTime;
            }
        );

        $firstAnswerEntity = $answerEntities[0];

        return ($firstAnswerEntity->rating > 0)
            ? $firstAnswerEntity
            : null;
    }
}
