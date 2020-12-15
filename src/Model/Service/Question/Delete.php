<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Delete
{
    public function __construct(
        QuestionTable\Question\QuestionId $questionIdTable
    ) {
        $this->questionIdTable = $questionIdTable;
    }

    public function delete(
        UserEntity\User $userEntity,
        string $reason,
        QuestionEntity\Question $questionEntity
    ): bool {
        return $this->questionIdTable->updateSetDeletedColumnsWhereQuestionId(
            $userEntity->getUserId(),
            $reason,
            $questionEntity->getQuestionId()
        );
    }
}
