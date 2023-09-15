<?php
namespace MonthlyBasis\Question\Model\Service\Questions\User;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Count
{
    public function __construct(
        protected QuestionTable\Question\UserId $userIdTable
    ) {
    }

    public function getCount(UserEntity\User $userEntity): int
    {
        $result = $this->userIdTable->selectCountWhereUserId(
            $userEntity->userId
        );
        return intval($result->current()['COUNT(*)']);
    }
}
