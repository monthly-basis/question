<?php
namespace MonthlyBasis\Question\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;

class QuestionFromAnswer
{
    protected array $cache;

    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory
    ) {
    }

    public function getQuestionFromAnswer(
        QuestionEntity\Answer $answerEntity
    ): QuestionEntity\Question {
        if (isset($this->cache[$answerEntity->answerId])) {
            return $this->cache[$answerEntity->answerId];
        }

        $questionEntity = $this->fromQuestionIdFactory->buildFromQuestionId(
            $answerEntity->getQuestionId()
        );

        $this->cache[$answerEntity->answerId] = $questionEntity;
        return $questionEntity;
    }
}
