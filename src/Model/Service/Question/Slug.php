<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Error;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;

class Slug
{
    public function __construct(
        protected QuestionService\Question\Title $titleService,
        protected StringService\UrlFriendly $urlFriendlyService
    ) {
    }

    public function getSlug(
        QuestionEntity\Question $questionEntity
    ): string {
        return $this->urlFriendlyService->getUrlFriendly(
            $this->titleService->getTitle($questionEntity)
        );
    }
}
