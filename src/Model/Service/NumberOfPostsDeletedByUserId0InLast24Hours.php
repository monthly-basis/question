<?php
namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;

class NumberOfPostsDeletedByUserId0InLast24Hours
{
    public function __construct(
        QuestionTable\Answer\CreatedIpDeletedDatetimeDeletedUserId $answerCreatedIpDeletedDatetimeDeletedUserIdTable,
        QuestionTable\Question\CreatedIpDeletedDatetimeDeletedUserId $questionCreatedIpDeletedDatetimeDeletedUserIdTable
    ) {
        $this->answerCreatedIpDeletedDatetimeDeletedUserIdTable   = $answerCreatedIpDeletedDatetimeDeletedUserIdTable;
        $this->questionCreatedIpDeletedDatetimeDeletedUserIdTable = $questionCreatedIpDeletedDatetimeDeletedUserIdTable;
    }

    public function getNumberOfPostsDeletedByUserInLast24Hours(
        $ipAddress
    ): int {
        $answerCount = $this->answerCreatedIpDeletedDatetimeDeletedUserIdTable->selectCountWhereCreatedIpAndDeletedGreaterThanOneDayAgoAndDeletedUserIdEquals0(
            $ipAddress
        );
        $questionCount = $this->questionCreatedIpDeletedDatetimeDeletedUserIdTable->selectCountWhereCreatedIpAndDeletedGreaterThanOneDayAgoAndDeletedUserIdEquals0(
            $ipAddress
        );
        return $answerCount + $questionCount;
    }
}
