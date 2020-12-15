<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Delete\Queue;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Add
{
    public function __construct(
        QuestionTable\AnswerDeleteQueue $answerDeleteQueueTable
    ) {
        $this->answerDeleteQueueTable = $answerDeleteQueueTable;
    }

    public function add(
        UserEntity\User $userEntity,
        QuestionEntity\Answer $answerEntity,
        string $reason
    ): bool {
        return (bool) $this->answerDeleteQueueTable->insert(
            $answerEntity->getAnswerId(),
            $userEntity->getUserId(),
            $reason
        );
    }
}
