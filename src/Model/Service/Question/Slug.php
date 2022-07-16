<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Error;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;

class Slug
{
    public function __construct(
        QuestionService\Question\Title $titleService,
        StringService\UrlFriendly $urlFriendlyService
    ) {
        $this->titleService       = $titleService;
        $this->urlFriendlyService = $urlFriendlyService;
    }

    public function getSlug(
        QuestionEntity\Question $questionEntity
    ): string {
        try {
            return $this->urlFriendlyService->getUrlFriendly(
                $questionEntity->getHeadline()
            );
        } catch (Error $error) {
            // Do nothing.
        }

        return $this->urlFriendlyService->getUrlFriendly(
            $this->titleService->getTitle($questionEntity)
        );
    }
}
