<?php
namespace MonthlyBasis\Question\Controller\Sitemaps;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Questions extends AbstractActionController
{
    public function __construct(
        protected QuestionService\Question\Questions $questionsService,
    ) {
    }

    public function questionsAction()
    {
        $this->getResponse()->getHeaders()->addHeaderLine(
            'Content-Type',
            'application/xml'
        );

        return (new ViewModel())
            ->setTerminal(true)
            ->setVariables([
                'questionEntities' => $this->questionsService->getQuestions(1),
            ]);
    }
}
