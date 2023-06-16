<?php
namespace MonthlyBasis\Question\View\Helper\Questions;

use Generator;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Trending extends AbstractHelper
{
    public function __construct(
        protected QuestionService\Question\Questions\MostPopular\Hour $hourService
    ) {
    }

    public function __invoke(): Generator
    {
        return $this->hourService->getQuestions();
    }
}
