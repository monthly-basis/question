<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Url
{
    public function __construct(
        QuestionService\Question\Url $questionUrlService,
        QuestionService\QuestionFromAnswer $questionFromAnswerService
    ) {
        $this->questionFromAnswerService = $questionFromAnswerService;
        $this->questionUrlService        = $questionUrlService;
    }

    public function getUrl(QuestionEntity\Answer $answerEntity): string
    {
        $questionEntity = $this->questionFromAnswerService->getQuestionFromAnswer(
            $answerEntity
        );

        return $this->questionUrlService->getUrl($questionEntity)
            . '#'
            . $answerEntity->getAnswerId();
    }
}
