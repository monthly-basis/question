<?php
namespace MonthlyBasis\Question\Model\Service\QuestionSearchMessage;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Rotate
{
    public function __construct(
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable
    ) {}

    public function rotate(): void
    {
        $this->questionSearchMessageTable->rotate();
    }
}
