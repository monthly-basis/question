<?php
namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class AnswerOrQuestionDeleted
{
    public function __construct(
        protected QuestionService\Answer\Deleted $answerDeletedService,
        protected QuestionService\Question\Deleted $questionDeletedService,
        protected QuestionService\QuestionFromAnswer $questionFromAnswerService,
    ) {
    }

    public function isAnswerOrQuestionDeleted(
        QuestionEntity\Answer $answerEntity
    ): bool {
        if ($this->answerDeletedService->isDeleted($answerEntity)) {
            return true;
        }

        $questionEntity = $this->questionFromAnswerService->getQuestionFromAnswer(
            $answerEntity
        );


        return $this->questionDeletedService->isDeleted($questionEntity);
    }
}
