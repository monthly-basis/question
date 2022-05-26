<?php
namespace MonthlyBasis\Question\Model\Service\Post;

use DateTime;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Duplicate
{
    public function __construct(
        QuestionTable\Answer\CreatedDatetime $createdDatetimeTable
    ) {
        $this->createdDatetimeTable = $createdDatetimeTable;
    }

    public function isDuplicate(
        string $createdIp,
        string $message
    ): bool {
        $createdDateTimeMin = (new DateTime())->modify('-1 day');

        $result = $this
            ->createdDatetimeTable
            ->selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
                $createdDateTimeMin,
                $createdIp,
                $message
        );

        return ($result->current()['COUNT(*)'] >= 2);
    }
}
