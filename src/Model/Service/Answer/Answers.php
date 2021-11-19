<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use Generator;
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
    ) : Generator {
        $result = $this->answerTable->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
            $questionEntity->getQuestionId()
        );
        foreach ($result as $array) {
            yield $this->answerFactory->buildFromArray($array);
        }
    }
}
