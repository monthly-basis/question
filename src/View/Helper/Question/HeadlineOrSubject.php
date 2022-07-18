<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class HeadlineOrSubject extends AbstractHelper
{
    public function __construct(
        QuestionService\Question\HeadlineOrSubject $headlineOrSubjectService
    ) {
        $this->headlineOrSubjectService = $headlineOrSubjectService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity)
    {
        return $this->headlineOrSubjectService->getHeadlineOrSubject(
            $questionEntity
        );
    }
}
