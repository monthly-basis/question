<?php
namespace MonthlyBasis\Question\Model\Service\Question\Slug;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;

class FromMessage
{
    public function __construct(
        protected StringService\StripTagsAndShorten $stripTagsAndShortenService,
        protected StringService\UrlFriendly $urlFriendlyService,
    ) {
    }

    public function getSlug(
        string $message
    ): string {
        $messageShortened = $this->stripTagsAndShortenService->stripTagsAndShorten(
            $message,
            90
        );

        return $this->urlFriendlyService->getUrlFriendly(
            $messageShortened
        );
    }
}
