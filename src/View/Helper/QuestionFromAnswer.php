<?php
namespace MonthlyBasis\Question\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class QuestionFromAnswer extends AbstractHelper
{
    public function __construct(
        QuestionService\QuestionFromAnswer $questionFromAnswerService
    ) {
        $this->questionFromAnswerService = $questionFromAnswerService;
    }

    /**
     * @throws \TypeError If question entity cannot be found
     */
    public function __invoke(
        QuestionEntity\Answer $answerEntity
    ): QuestionEntity\Question {
        return $this->questionFromAnswerService->getQuestionFromAnswer(
            $answerEntity
        );
    }
}
