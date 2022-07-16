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

    /**
     * @todo This can probably be updated to just get url-friendly version
     * of the title. However, let's just update title logic for now and see
     * how things go.
     */
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
