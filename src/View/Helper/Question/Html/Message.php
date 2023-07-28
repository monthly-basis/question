<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Message extends AbstractHelper
{
    public function __construct(
        ContentModerationService\ToHtml $toHtmlService
    ) {
        $this->toHtmlService = $toHtmlService;
    }

    public function __invoke(
        QuestionEntity\Question $questionEntity,
        string $headingTagEscaped = 'h1',
    ): string {
        if (!in_array($headingTagEscaped, ['h1', 'h2', 'h3'])) {
            $headingTagEscaped = 'h1';
        }

        try {
            $message = $questionEntity->getMessage();
        } catch (Error $error) {
            return '';
        }

        $messageHtml = $this->toHtmlService->toHtml($message);

        if (strlen($messageHtml) == 0) {
            $messageHtml = '';
            return $messageHtml;
        }

        $messageHtmlLines         = preg_split("/<br>\n/", $messageHtml);
        $numberOfMessageHtmlLines = count($messageHtmlLines);

        if ($numberOfMessageHtmlLines == 1) {
            $messageHtml = "<$headingTagEscaped "
                . 'itemprop="name">'
                . $messageHtmlLines[0]
                . "</$headingTagEscaped>";
            return $messageHtml;
        }

        $restOfMessageHtmlLines = array_slice($messageHtmlLines, 1);

        /*
         * $restOfMessageHtmlLines[0] is the line after the h1.
         * If it has strlen == 0, then margin-bottom of h1 should be unspecified,
         * and the blank line can be removed.
         *
         * If it has strlen > 0, then margin-bottom of h1 should be 0.
         */
        if (strlen($restOfMessageHtmlLines[0]) == 0) {
            $h1ClassKeyValue = '';
            array_shift($restOfMessageHtmlLines);
        } else {
            $h1ClassKeyValue = ' class="mb-0"';
        }

        $messageHtml = "<$headingTagEscaped" . $h1ClassKeyValue . ' itemprop="name">'
            . $messageHtmlLines[0]
            . "</$headingTagEscaped>" . "\n";

        $messageHtml .= '<p itemprop="text">' . "\n"
            . implode("<br>\n", $restOfMessageHtmlLines) . "\n"
            . '</p>';

        return $messageHtml;
    }
}
