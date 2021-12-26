<?php
namespace MonthlyBasis\Question\Model\Factory\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class FromQuestionId
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function buildFromQuestionId(
        int $questionId
    ): QuestionEntity\Question {
        return $this->questionFactory->buildFromArray(
            $this->questionTable->selectWhereQuestionId($questionId)
        );
    }
}
