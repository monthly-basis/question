<?php
namespace MonthlyBasis\Question\Model\Service\Answers;

use Generator;
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

    public function getAnswers(
        UserEntity\User $userEntity,
        int $page
    ): Generator {
        $result = $this->answerTable->selectWhereUserIdOrderByCreatedDatetimeDesc(
            $userEntity->getUserId(),
            ($page - 1) * 100,
            100
        );
        foreach ($result as $array) {
            yield $this->answerFactory->buildFromArray($array);
        }
    }
}
