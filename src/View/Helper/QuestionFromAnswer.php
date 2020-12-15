<?php
namespace MonthlyBasis\Question\View\Helper;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use Laminas\View\Helper\AbstractHelper;

class QuestionFromAnswer extends AbstractHelper
{
    /**
     * Construct.
     *
     * @param QuestionService\QuestionFromAnswer $questionFromAnswerService
     */
    public function __construct(
        QuestionService\QuestionFromAnswer $questionFromAnswerService
    ) {
        $this->questionFromAnswerService = $questionFromAnswerService;
    }

    /**
     * Invoke.
     *
     * @param QuestionEntity\Answer $answerEntity
     * @throws TypeError If question entity cannot be found
     * @return QuestionEntity\Question
     */
    public function __invoke(
        QuestionEntity\Answer $answerEntity
    ): QuestionEntity\Question {
        return $this->questionFromAnswerService->getQuestionFromAnswer(
            $answerEntity
        );
    }
}
