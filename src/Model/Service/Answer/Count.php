<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Count
{
    public function __construct(
        QuestionTable\Answer $answerTable
    ) {
        $this->answerTable   = $answerTable;
    }

    /**
     * Get count.
     *
     * @param QuestionEntity\Question $questionEntity
     * @return int
     */
    public function getCount(
        QuestionEntity\Question $questionEntity
    ) : int {
        return $this->answerTable->selectCountWhereQuestionId(
            $questionEntity->getQuestionId()
        );
    }
}
