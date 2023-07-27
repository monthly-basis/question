<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;

class Title
{
    public function __construct(
        protected StringService\StripTagsAndShorten $stripTagsAndShortenService
    ) {
    }

    public function getTitle(
        QuestionEntity\Answer $answerEntity
    ): string {
        return $this->stripTagsAndShortenService->stripTagsAndShorten(
            $answerEntity->message,
            90
        );
    }
}
