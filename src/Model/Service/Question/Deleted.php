<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Deleted
{
    public function isDeleted(QuestionEntity\Question $questionEntity): bool
    {
        return isset($questionEntity->deletedDateTime);
    }
}
