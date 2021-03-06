<?php
namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;

class QuestionFromAnswer
{
    /**
     * Construct.
     *
     * @param QuestionFactory\Question $questionFactory
     */
    public function __construct(
        QuestionFactory\Question $questionFactory
    ) {
        $this->questionFactory = $questionFactory;
    }

    /**
     * Get question from answer.
     *
     * @return QuestionEntity\Question
     */
    public function getQuestionFromAnswer(
        QuestionEntity\Answer $answerEntity
    ): QuestionEntity\Question {
        return $this->questionFactory->buildFromQuestionId(
            $answerEntity->getQuestionId()
        );
    }
}
