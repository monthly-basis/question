<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Deleted
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer $answerTable
    ) {
        $this->answerFactory = $answerFactory;
        $this->answerTable   = $answerTable;
    }

    public function insert(): QuestionEntity\Answer
    {
        $answerId = $this->answerTable->insertDeleted(
            $_POST['question-id'],
            null,
            $_POST['message'],
            $_POST['name'],
            $_SERVER['REMOTE_ADDR'],
            0,
            'foul language'
        );

        return $this->answerFactory->buildFromAnswerId($answerId);
    }
}
