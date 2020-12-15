<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Delete\Queue;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Decline
{
    public function __construct(
        QuestionTable\AnswerDeleteQueue $answerDeleteQueueTable
    ) {
        $this->answerDeleteQueueTable = $answerDeleteQueueTable;
    }

    public function decline(
        int $answerDeleteQueueId
    ) {
        $this->answerDeleteQueueTable->updateSetQueueStatusIdWhereAnswerDeleteQueueId(
            -1,
            $answerDeleteQueueId
        );
    }
}
