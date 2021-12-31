<?php
namespace MonthlyBasis\Question\Model\Service\Post\Posts;

use DateTime;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class NumberOfPostsRecentlyDeletedForFoulLanguage
{
    public function __construct(
        QuestionTable\Answer\CreatedIp $createdIpAnswerTable,
        QuestionTable\Question\CreatedIp $createdIpQuestionTable
    ) {
        $this->createdIpAnswerTable   = $createdIpAnswerTable;
        $this->createdIpQuestionTable = $createdIpQuestionTable;
    }

    public function getNumberOfPostsRecentlyDeletedForFoulLanguage(
        string $ipAddress
    ): int {
        $result = $this->createdIpAnswerTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            $ipAddress,
            (new DateTime())->modify('-1 day'),
            0,
            'foul language',
        );
        $answerCount = intval($result->current()['COUNT(*)']);

        $result = $this->createdIpQuestionTable->selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
            $ipAddress,
            (new DateTime())->modify('-1 day'),
            0,
            'foul language',
        );
        $questionCount = intval($result->current()['COUNT(*)']);

        return $answerCount + $questionCount;
    }
}
