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

    public function insert(): QuestionEntity\Question {
        $questionId = $this->questionTable->insertDeleted(
            null,
            $_POST['subject'],
            $_POST['message'],
            $_POST['name'],
            $_SERVER['REMOTE_ADDR'],
            0,
            'foul language'
        );

        return $this->questionFactory->buildFromQuestionId($questionId);
    }
}
