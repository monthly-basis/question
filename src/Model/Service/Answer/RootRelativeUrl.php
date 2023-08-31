<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class RootRelativeUrl
{
    public function __construct(
        protected QuestionEntity\Config $configEntity,
        protected QuestionService\Answer\Slug $slugService,
    ) {}

    public function getRootRelativeUrl(
        QuestionEntity\Answer $answerEntity
    ): string {
        $rootRelativeUrl = '/answers/'
            . $answerEntity->getAnswerId();

        $includeSlug = $this->configEntity['answer']['root-relative-url']['include-slug'] ?? true;

        if ($includeSlug) {
            $rootRelativeUrl .= '/' . $this->slugService->getSlug($answerEntity);
        }

        return $rootRelativeUrl;
    }
}
