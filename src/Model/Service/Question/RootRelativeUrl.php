<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class RootRelativeUrl
{
    public function __construct(
        QuestionEntity\Config $configEntity,
        QuestionService\Question\Slug $slugService
    ) {
        $this->configEntity = $configEntity;
        $this->slugService  = $slugService;
    }

    public function getRootRelativeUrl(
        QuestionEntity\Question $questionEntity
    ): string {
        $rootRelativeUrl = '/'
            . $questionEntity->getQuestionId()
            . '/'
            . $this->slugService->getSlug($questionEntity);

        $pathBeforeQuestionId
            = $this->configEntity['question']['root-relative-url']['path-before-question-id']
            ?? '/questions'
            ;

        $rootRelativeUrl = $pathBeforeQuestionId . $rootRelativeUrl;

        return $rootRelativeUrl;
    }
}
