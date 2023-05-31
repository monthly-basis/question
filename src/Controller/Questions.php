<?php
namespace MonthlyBasis\Question\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Questions extends AbstractActionController
{
    public function __construct(
        protected QuestionService\Question\Questions\Newest $newestQuestionsService
    ) {}

    public function indexAction()
    {
        return [
            'newestQuestions' => $this->newestQuestionsService->getNewestQuestions(),
        ];
    }
}
