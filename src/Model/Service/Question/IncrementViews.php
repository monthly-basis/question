<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class IncrementViews
{
    public function __construct(
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function incrementViews(
        QuestionEntity\Question $questionEntity
    ): bool {
        return $this->questionTable->updateViewsWhereQuestionId(
            $questionEntity->getQuestionId()
        );
    }
}
