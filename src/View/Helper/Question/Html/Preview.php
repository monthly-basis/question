<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Preview extends AbstractHelper
{
    public function __construct(
        protected ContentModerationService\Replace\BadWords $replaceBadWordsService,
        protected ContentModerationService\Replace\LineBreaks $replaceLineBreaksService,
        protected ContentModerationService\Replace\Spaces $replaceSpacesService,
        protected StringService\Escape $escapeService,
        protected StringService\Shorten $shortenService,
    ) {}

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        try {
            $message = $questionEntity->getMessage();
        } catch (Error $error) {
            return '';
        }

        if (strlen($message) == 0) {
            return '';
        }

        $message = $this->replaceBadWordsService->replaceBadWords($message, '');
        $message = $this->replaceLineBreaksService->replaceLineBreaks($message);
        $message = trim($message);

        $lines     = preg_split('/\n/', $message);
        $firstLine = $lines[0];
        $firstLine = $this->replaceSpacesService->replaceSpaces($firstLine);

        if (strlen($firstLine) > 128) {
            $firstLine = $this->shortenService->shorten(
                $firstLine,
                128
            );
            return '<b class="a-c-e">'
                . $this->escapeService->escape($firstLine)
                . '</b>';
        }

        $firstLineEscaped = $this->escapeService->escape($firstLine);

        if (count($lines) == 1) {
            return '<b>' . $firstLineEscaped . '</b>';
        }

        $restOfLines = array_slice($lines, 1);
        $restOfLines = implode(' ', $restOfLines);
        $restOfLines = $this->replaceSpacesService->replaceSpaces($restOfLines);

        $charactersRemaining = 128 - strlen($firstLine);

        if (strlen($restOfLines) > $charactersRemaining) {
            $restOfLines = $this->shortenService->shorten(
                $restOfLines,
                $charactersRemaining
            );
            return '<b>' . $firstLineEscaped . '</b><br>'
                . '<span class="a-c-e">'
                . $this->escapeService->escape($restOfLines)
                . '</span>';
        }

        return '<b>' . $firstLineEscaped . '</b><br>'
            . '<span>'
            . $this->escapeService->escape($restOfLines)
            . '</span>';
    }
}
