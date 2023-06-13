<?php
namespace MonthlyBasis\Question\Model\Service\Question\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Deleted
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question $questionTable
    ) {
        $this->questionFactory = $questionFactory;
        $this->questionTable   = $questionTable;
    }

    public function insert(
        string $reason = 'foul language'
    ): QuestionEntity\Question {
        $questionId = $this->questionTable->insertDeleted(
            null,
            $_POST['subject'] ?? null,
            $_POST['message'],
            $_POST['name'] ?? null,
            $_SERVER['REMOTE_ADDR'],
            0,
            $reason,
        );

        return $this->questionFactory->buildFromQuestionId($questionId);
    }
}
