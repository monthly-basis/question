<?php
namespace MonthlyBasis\Question\Model\Service\Answer\Delete\Queue;

use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Factory as UserFactory;

class Approve
{
    public function __construct(
        QuestionTable\Answer\AnswerId $answerIdTable,
        QuestionTable\AnswerDeleteQueue $answerDeleteQueueTable
    ) {
        $this->answerIdTable          = $answerIdTable;
        $this->answerDeleteQueueTable = $answerDeleteQueueTable;
    }

    public function approve(
        int $answerDeleteQueueId
    ) {
        $array = $this->answerDeleteQueueTable->selectWhereAnswerDeleteQueueId(
            $answerDeleteQueueId
        );

        $this->answerIdTable->updateSetDeletedColumnsWhereAnswerId(
            $array['user_id'],
            $array['reason'],
            $array['answer_id']
        );

        $this->answerDeleteQueueTable->updateSetQueueStatusIdWhereAnswerDeleteQueueId(
            1,
            $answerDeleteQueueId
        );
    }
}
