<?php
namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;

class QuestionFromAnswer
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory
    ) {
    }

    public function getQuestionFromAnswer(
        QuestionEntity\Answer $answerEntity
    ): QuestionEntity\Question {
        return $this->fromQuestionIdFactory->buildFromQuestionId(
            $answerEntity->getQuestionId()
        );
    }
}
