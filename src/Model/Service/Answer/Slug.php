<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;

class Slug
{
    public function __construct(
        protected StringService\StripTagsAndShorten $stripTagsAndShortenService,
        protected StringService\UrlFriendly $urlFriendlyService,
    ) {}

    public function getSlug(
        QuestionEntity\Answer $answerEntity
    ): string {
        return $this->urlFriendlyService->getUrlFriendly(
            $this->stripTagsAndShortenService->stripTagsAndShorten(
                $answerEntity->getMessage(),
                90
            )
        );
    }
}
