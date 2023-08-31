<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use Error;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;

class Title
{
    public function __construct(
        StringService\StripTagsAndShorten $stripTagsAndShortenService
    ) {
        $this->stripTagsAndShortenService = $stripTagsAndShortenService;
    }

    public function getTitle(
        QuestionEntity\Question $questionEntity
    ): string {
        try {
            return $questionEntity->getHeadline();
        } catch (Error $error) {
            // Do nothing.
        }

        return $this->stripTagsAndShortenService->stripTagsAndShorten(
            $questionEntity->getMessage(),
            60
        );

        /*
        # If there is a question mark after the 20th character,
        # then truncate the message at the question mark.
        $questionMarkPosition = strpos($message, '?', 20);
        if ($questionMarkPosition > 20) {
            $message = substr($message, 0, $questionMarkPosition + 1);
        }
         */
    }
}
