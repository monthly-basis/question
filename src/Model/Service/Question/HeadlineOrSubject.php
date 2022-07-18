<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Error;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class HeadlineOrSubject
{
    public function getHeadlineOrSubject(
        QuestionEntity\Question $questionEntity
    ): string {
        try {
            return $questionEntity->getHeadline();
        } catch (Error $error) {
            // Do nothing.
        }

        return $questionEntity->getSubject();
    }
}
