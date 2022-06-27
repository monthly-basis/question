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

    public function getRootRelativeUrl(
        QuestionEntity\Question $questionEntity,
        bool $includeQuestionsDirectory = true
    ): string {
        $title = $this->titleService->getTitle($questionEntity);

        $rootRelativeUrl = '/'
            . $questionEntity->getQuestionId()
            . '/'
            . $this->urlFriendlyService->getUrlFriendly($title);

        if ($includeQuestionsDirectory) {
            $rootRelativeUrl = '/questions' . $rootRelativeUrl;
        }

        return $rootRelativeUrl;
    }
}
