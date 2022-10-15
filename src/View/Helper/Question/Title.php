<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Title extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\Title $titleService
    ) {
        $this->titleService = $titleService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        return $this->titleService->getTitle(
            $questionEntity
        );
    }
}
