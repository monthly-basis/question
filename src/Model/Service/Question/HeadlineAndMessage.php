<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Error;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class HeadlineAndMessage
{
    public function getHeadlineAndMessage(
        QuestionEntity\Question $questionEntity
    ): string {
        $parts = [];

        try {
            $parts[] = $questionEntity->getHeadline();
        } catch (Error $error) {
            // Do nothing.
        }
        try {
            $parts[] = $questionEntity->getMessage();
        } catch (Error $error) {
            // Do nothing.
        }

        return implode(' ', $parts);
    }
}
