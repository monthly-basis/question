<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Url extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\Url $urlService
    ) {
        $this->urlService = $urlService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        return $this->urlService->getUrl(
            $questionEntity
        );
    }
}
