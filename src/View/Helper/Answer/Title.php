<?php
namespace MonthlyBasis\Question\View\Helper\Answer;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Title extends AbstractHelper
{
    public function __construct(
        protected QuestionService\Answer\Title $titleService
    ) {
    }

    public function __invoke(QuestionEntity\Answer $answerEntity): string
    {
        return $this->titleService->getTitle(
            $answerEntity
        );
    }
}
