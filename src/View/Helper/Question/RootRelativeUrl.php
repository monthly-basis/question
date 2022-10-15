<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class RootRelativeUrl extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\RootRelativeUrl $rootRelativeUrlService
    ) {
        $this->rootRelativeUrlService = $rootRelativeUrlService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        return $this->rootRelativeUrlService->getRootRelativeUrl(
            $questionEntity
        );
    }
}
