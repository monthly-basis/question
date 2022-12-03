<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Deleted
{
    public function isDeleted(QuestionEntity\Answer $answerEntity): bool
    {
        try {
            $answerEntity->getDeletedDateTime();
            return true;
        } catch (\Error) {
            return false;
        }
    }
}
