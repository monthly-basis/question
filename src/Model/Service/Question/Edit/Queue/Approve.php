<?php
namespace MonthlyBasis\Question\Model\Service\Question\Edit\Queue;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Approve
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionService\Question\Edit $editService,
        QuestionTable\QuestionEditQueue $questionEditQueueTable
    ) {
        $this->questionFactory        = $questionFactory;
        $this->editService            = $editService;
        $this->questionEditQueueTable = $questionEditQueueTable;
    }

    public function approve(
        int $questionEditQueueId
    ) {
        $array = $this->questionEditQueueTable->selectWhereQuestionEditQueueId(
            $questionEditQueueId
        );
        $this->editService->edit(
            $this->questionFactory->buildFromQuestionId($array['question_id']),
            $array['name'],
            $array['subject'],
            $array['message'],
            $array['user_id'],
            $array['reason']
        );
        $this->questionEditQueueTable->updateSetQueueStatusIdWhereQuestionEditQueueId(
            1,
            $questionEditQueueId
        );
    }
}
