<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

/**
 * Right now, this Related service is just a wrapper for Similar service.
 * However, if Related means something different than Similar in the future,
 * then code in Related and Similar can be modified individually.
 */
class Related
{
    public function __construct(
        QuestionService\Question\Questions\Similar $similarService
    ) {
        $this->similarService = $similarService;
    }

    public function getRelated(
        QuestionEntity\Question $questionEntity,
        int $outerLimitOffset = 0,
        int $outerLimitRowCount = 20,
    ): Generator {
        return $this->similarService->getSimilar(
            questionEntity: $questionEntity,
            outerLimitOffset: $outerLimitOffset,
            outerLimitRowCount: $outerLimitRowCount,
        );
    }
}
