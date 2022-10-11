<?php
namespace MonthlyBasis\Question\Model\Service\Question\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class User
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function insert(
        UserEntity\User $userEntity
    ): QuestionEntity\Question {
        $questionId = $this->questionTable->insertDeprecated(
            $userEntity->getUserId(),
            $_POST['subject'],
            $_POST['message'],
            $_POST['name'],
            $_SERVER['REMOTE_ADDR']
        );

        return $this->questionFactory->buildFromQuestionId($questionId);
    }
}
