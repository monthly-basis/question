<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html\P;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

/**
 * Get entire message as part of <p> tag.
 *
 * You will eventually want to use a QuestionHelper\Question\Html\Message helper
 * (instead of this QuestionHelper\Question\Html\P\Message helper)
 * to get the entire message divided up into an <h1 class="message">
 * and <p class="message"> tag.
 */
class Message extends AbstractHelper
{
    public function __construct(
        ContentModerationService\ToHtml $toHtmlService
    ) {
        $this->toHtmlService = $toHtmlService;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        try {
            return '<p class="message">'
                 . $this->toHtmlService->toHtml($questionEntity->getMessage())
                 . '</p>';
        } catch (Error $error) {
            return '';
        }

    }
}
