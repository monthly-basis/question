<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use Laminas\View\Helper\AbstractHelper;

class Title extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\Title $titleService
    ) {
        $this->titleService = $titleService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity)
    {
        return $this->titleService->getTitle(
            $questionEntity
        );
    }
}
