<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class User
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer $answerTable
    ) {
        $this->answerFactory = $answerFactory;
        $this->answerTable   = $answerTable;
    }

    public function insert(
        UserEntity\User $userEntity
    ): QuestionEntity\Answer {
        $answerId = $this->answerTable->insertDeprecated(
            $_POST['question-id'],
            $userEntity->getUserId(),
            $_POST['message'],
            $_POST['name'],
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']
        );

        return $this->answerFactory->buildFromAnswerId($answerId);
    }
}
