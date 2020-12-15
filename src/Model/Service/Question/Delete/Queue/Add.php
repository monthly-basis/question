<?php
namespace MonthlyBasis\Question\Model\Service\Question\Delete\Queue;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Add
{
    public function __construct(
        QuestionTable\QuestionDeleteQueue $questionDeleteQueueTable
    ) {
        $this->questionDeleteQueueTable = $questionDeleteQueueTable;
    }

    public function add(
        UserEntity\User $userEntity,
        QuestionEntity\Question $questionEntity,
        string $reason
    ): bool {
        return (bool) $this->questionDeleteQueueTable->insert(
            $questionEntity->getQuestionId(),
            $userEntity->getUserId(),
            $reason
        );
    }
}
