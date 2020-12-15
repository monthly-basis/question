<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Edit\Queue;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Decline
{
    public function __construct(
        QuestionTable\AnswerEditQueue $answerEditQueueTable
    ) {
        $this->answerEditQueueTable = $answerEditQueueTable;
    }

    public function decline(
        int $answerEditQueueId
    ) {
        $this->answerEditQueueTable->updateSetQueueStatusIdWhereAnswerEditQueueId(
            -1,
            $answerEditQueueId
        );
    }
}
