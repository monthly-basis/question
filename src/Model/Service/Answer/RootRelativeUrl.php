<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class RootRelativeUrl
{
    public function __construct(
        protected QuestionService\Answer\Slug $slugService
    ) {}

    public function getRootRelativeUrl(
        QuestionEntity\Answer $answerEntity
    ): string {
        return '/answers/'
            . $answerEntity->getAnswerId()
            . '/'
            . $this->slugService->getSlug($answerEntity);
    }
}
