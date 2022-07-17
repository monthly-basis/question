<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;

class H2 extends AbstractHelper
{
    public function __construct(
        StringService\Escape $escapeService
    ) {
        $this->escapeService = $escapeService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        try {
            $headlineOrSubject = $questionEntity->getHeadline();
        } catch (Error $error) {
            $headlineOrSubject = $questionEntity->getSubject();
        }

        return '<h2>'
             . $this->escapeService->escape($headlineOrSubject)
             . '</h2>';
    }
}
