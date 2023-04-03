<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class IncrementAnswerCountCached
{
    public function __construct(
        protected QuestionTable\Question\QuestionId $questionIdTable
    ) {
    }

    public function incrementAnswerCountCached(
        QuestionEntity\Question $questionEntity
    ): bool {
        $result = $this->questionIdTable->updateAnswerCountCachedWhereQuestionId(
            $questionEntity->getQuestionId()
        );
        return boolval($result->getAffectedRows());
    }
}
