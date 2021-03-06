<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;

class RootRelativeUrl
{
    public function __construct(
        QuestionService\Question\Title $titleService,
        StringService\UrlFriendly $urlFriendlyService
    ) {
        $this->titleService       = $titleService;
        $this->urlFriendlyService = $urlFriendlyService;
    }

    /**
     * Get root-relative URL.
     *
     * @param QuestionEntity\Question $questionEntity
     * @return string
     */
    public function getRootRelativeUrl($questionEntity) : string
    {
        $title = $this->titleService->getTitle($questionEntity);

        return '/questions/'
             . $questionEntity->getQuestionId()
             . '/'
             . $this->urlFriendlyService->getUrlFriendly($title);
    }
}
