<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class User
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(
        UserEntity\User $userEntity,
        int $page
    ): Generator {
        $result = $this->questionTable->selectWhereUserIdOrderByCreatedDatetimeDesc(
            $userEntity->getUserId(),
            ($page - 1) * 100,
            100
        );
        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
