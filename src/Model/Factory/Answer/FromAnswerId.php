<?php
namespace MonthlyBasis\Question\Model\Factory\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class FromAnswerId
{
    public function __construct(
        protected QuestionFactory\Answer $answerFactory,
        protected QuestionTable\Answer $answerTable
    ) {
    }

    public function buildFromAnswerId(int $answerId): QuestionEntity\Answer
    {
        return $this->answerFactory->buildFromArray(
            $this->answerTable->selectWhereAnswerId($answerId)
        );
    }
}
