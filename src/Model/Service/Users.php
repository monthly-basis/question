<?php
namespace MonthlyBasis\Question\Model\Service;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Factory as UserFactory;

class Users
{
    public function __construct(
        protected QuestionTable\Question\UserId $userIdTable,
        protected UserFactory\User $userFactory,
    ) {
    }

    public function getUsers(): Generator
    {
        $result = $this->userIdTable->selectUserIdOrderByMaxCreatedDatetime();
        foreach ($result as $array) {
            yield ($this->userFactory->buildFromUserId($array['user_id']));
        }
    }
}
