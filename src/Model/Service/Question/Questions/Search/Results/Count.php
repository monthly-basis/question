<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\Search\Results;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;

class Count
{
    public function __construct(
        QuestionTable\QuestionSearchMessage $questionSearchMessage,
        StringService\KeepFirstWords $keepFirstWordsService
    ) {
        $this->questionSearchMessage = $questionSearchMessage;
        $this->keepFirstWordsService = $keepFirstWordsService;
    }

    public function getCount(string $query): int
    {
        $query = strtolower($query);
        $query = $this->keepFirstWordsService->keepFirstWords(
            $query,
            16
        );

        $result = $this->questionSearchMessage->selectCountWhereMatchMessageAgainst(
            $query
        );
        return $result->current()['COUNT(*)'];
    }
}
