<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;

class RootRelativeUrl
{
    public function __construct(
        QuestionEntity\Config $configEntity,
        QuestionService\Question\Title $titleService,
        StringService\UrlFriendly $urlFriendlyService
    ) {
        $this->configEntity       = $configEntity;
        $this->titleService       = $titleService;
        $this->urlFriendlyService = $urlFriendlyService;
    }

    public function getRootRelativeUrl(
        QuestionEntity\Question $questionEntity
    ): string {
        $title = $this->titleService->getTitle($questionEntity);

        $rootRelativeUrl = '/'
            . $questionEntity->getQuestionId()
            . '/'
            . $this->urlFriendlyService->getUrlFriendly($title);

        $pathBeforeQuestionId
            = $this->configEntity['question']['root-relative-url']['path-before-question-id']
            ?? '/questions'
            ;

        $rootRelativeUrl = $pathBeforeQuestionId . $rootRelativeUrl;

        return $rootRelativeUrl;
    }
}
