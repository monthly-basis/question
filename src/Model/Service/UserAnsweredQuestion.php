<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class UserAnsweredQuestion
{
    public function __construct(
        protected QuestionTable\Answer $answerTable,
    ) {
    }

    public function didUserAnswerQuestion(
        UserEntity\User $userEntity,
        QuestionEntity\Question $questionEntity,
    ): bool
    {
        $result = $this->answerTable->selectCountWhereQuestionIdAndUserId(
            $questionEntity->questionId,
            $userEntity->userId,
        );
        return boolval($result->current()['COUNT(*)']);
    }
}
