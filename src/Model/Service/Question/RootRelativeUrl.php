<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class RootRelativeUrl
{
    protected array $cache;

    public function __construct(
        protected QuestionEntity\Config $configEntity,
        protected QuestionService\Question\Slug $slugService
    ) {
    }

    public function getRootRelativeUrl(
        QuestionEntity\Question $questionEntity
    ): string {
        if (isset($this->cache[$questionEntity->questionId])) {
            return $this->cache[$questionEntity->questionId];
        }

        $rootRelativeUrl
            = $this->configEntity['question']['root-relative-url']['path-before-question-id']
            ?? '/questions'
            ;

        $includeQuestionId = $this->configEntity['question']['root-relative-url']['include-question-id'] ?? true;

        if ($includeQuestionId) {
            $rootRelativeUrl .= '/'
                . $questionEntity->getQuestionId();
        }

        $includeSlug = $this->configEntity['question']['root-relative-url']['include-slug'] ?? true;

        if ($includeSlug) {
            $rootRelativeUrl .= '/'
                . $this->slugService->getSlug($questionEntity);
        }

        $this->cache[$questionEntity->questionId] = $rootRelativeUrl;
        return $rootRelativeUrl;
    }
}
