<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Queue;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class Insert
{
    public function __construct(
        protected QuestionTable\AnswerQueue $answerQueueTable
    ) {
    }

    public function insert(
        int $questionId,
        string $message,
        string $createdIp,
        string $name = null,
        int $createdUserId = null,
    ) {
        $this->answerQueueTable->insert(
            values: [
                'question_id'   => $questionId,
                'message'       => $message,
                'created_ip'    => $createdIp,
                'name'          => $name,
                'createdUserId' => $createdUserId,
            ],
        );
    }
}
