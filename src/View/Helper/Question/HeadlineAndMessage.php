<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use Laminas\View\Helper\AbstractHelper;

class HeadlineAndMessage extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\HeadlineAndMessage $headlineAndMessageService
    ) {
        $this->headlineAndMessageService = $headlineAndMessageService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity)
    {
        return $this->headlineAndMessageService->getHeadlineAndMessage(
            $questionEntity
        );
    }
}
