<?php
namespace MonthlyBasis\Question\Model\Service\QuestionSearchSimilar;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Rotate
{
    public function __construct(
        protected QuestionTable\QuestionSearchSimilar $questionSearchSimilarTable
    ) {}

    public function rotate(): void
    {
        $this->questionSearchSimilarTable->rotate();
    }
}
