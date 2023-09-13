<?php
namespace MonthlyBasis\Question\Model\Service\Answers\CreatedName;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Count
{
    public function __construct(
        protected QuestionTable\Answer\CreatedName $createdNameTable
    ) {
    }

    public function getCount(string $createdName): int
    {
        $result = $this->createdNameTable->selectCountWhereCreatedName(
            $createdName
        );
        return intval($result->current()['COUNT(*)']);
    }
}
