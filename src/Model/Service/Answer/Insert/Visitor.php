<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Visitor
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
        $answerId = $this->answerTable->insert(
            $_POST['question-id'],
            null,
            $_POST['message'],
            $_POST['name'],
            $_SERVER['REMOTE_ADDR']
        );

        return $this->answerFactory->buildFromAnswerId($answerId);
    }
}
