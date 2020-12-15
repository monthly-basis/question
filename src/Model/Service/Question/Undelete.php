<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Undelete
{
    public function __construct(
        QuestionTable\Question\QuestionId $questionIdTable
    ) {
        $this->questionIdTable = $questionIdTable;
    }

    public function undelete(
        QuestionEntity\Question $questionEntity
    ): bool {
        return (bool) $this->questionIdTable->updateSetDeletedColumnsToNullWhereQuestionId(
            $questionEntity->getQuestionId()
        );
    }
}
