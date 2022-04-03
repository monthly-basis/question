<?php
namespace MonthlyBasis\Question\View\Helper\Post;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class CanBeUndeleted extends AbstractHelper
{
    public function __construct(
        QuestionService\Post\CanBeUndeleted $canBeUndeletedService
    ) {
        $this->canBeUndeletedService = $canBeUndeletedService;
    }

    public function __invoke(QuestionEntity\Post $postEntity)
    {
        return $this->canBeUndeletedService->canBeUndeleted(
            $postEntity
        );
    }
}
