<?php
namespace MonthlyBasis\Question\Model\Service\AnswerSearchMessage;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Rotate
{
    public function __construct(
        protected QuestionTable\AnswerSearchMessage $answerSearchMessageTable
    ) {}

    public function rotate(): void
    {
        $this->answerSearchMessageTable->rotate();
    }
}
