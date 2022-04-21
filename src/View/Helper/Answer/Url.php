<?php
namespace MonthlyBasis\Question\View\Helper\Answer;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Url extends AbstractHelper
{
    public function __construct(
        QuestionService\Answer\Url $urlService
    ) {
        $this->urlService = $urlService;
    }

    public function __invoke(QuestionEntity\Answer $answerEntity)
    {
        return $this->urlService->getUrl(
            $answerEntity
        );
    }
}
