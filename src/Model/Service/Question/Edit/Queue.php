<?php
namespace MonthlyBasis\Question\Model\Service\Question\Edit;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Queue
{
    public function __construct(
        QuestionTable\QuestionEditQueue $questionEditQueueTable
    ) {
        $this->questionEditQueueTable = $questionEditQueueTable;
    }

    public function queue(
        QuestionEntity\Question $questionEntity,
        UserEntity\User $userEntity,
        string $name = null,
        string $subject,
        string $message,
        string $ip,
        string $reason
    ) {
        $questionEditQueueId = $this->questionEditQueueTable->insert(
            $questionEntity->getQuestionId(),
            $userEntity->getUserId(),
            $name,
            $subject,
            $message,
            $ip,
            $reason
        );
    }
}
