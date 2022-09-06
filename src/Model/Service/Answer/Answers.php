<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Answers
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer $answerTable
    ) {
        $this->answerFactory = $answerFactory;
        $this->answerTable   = $answerTable;
    }

    public function getAnswers(
        QuestionEntity\Question $questionEntity
    ): array {
        $result = $this->answerTable->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
            $questionEntity->getQuestionId()
        );

        $answerEntities = [];
        foreach ($result as $array) {
            $answerEntities[] = $this->answerFactory->buildFromArray($array);
        }

        return $answerEntities;
    }
}
