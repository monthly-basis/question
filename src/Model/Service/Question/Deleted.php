<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Deleted
{
    public function isDeleted(QuestionEntity\Question $questionEntity): bool
    {
        try {
            $questionEntity->getDeletedDateTime();
            return true;
        } catch (\Error) {
            return false;
        }
    }
}
