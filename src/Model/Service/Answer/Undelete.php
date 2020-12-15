<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Undelete
{
    public function __construct(
        QuestionTable\Answer\AnswerId $answerIdTable
    ) {
        $this->answerIdTable = $answerIdTable;
    }

    public function undelete(
        QuestionEntity\Answer $answerEntity
    ): bool {
        return (bool) $this->answerIdTable->updateSetDeletedColumnsToNullWhereAnswerId(
            $answerEntity->getAnswerId()
        );
    }
}
