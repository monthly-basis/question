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

        if (strlen($firstLine) > 256) {
            $firstLine = $this->shortenService->shorten(
                $firstLine,
                256
            );
            return '<h3 class="a-c-e">'
                . $this->escapeService->escape($firstLine)
                . '</h3>';
        }

        $firstLineEscaped = $this->escapeService->escape($firstLine);

        if (count($lines) == 1) {
            return '<h3>' . $firstLineEscaped . '</h3>';
        }

        $restOfLines = array_slice($lines, 1);
        $restOfLines = implode(' ', $restOfLines);
        $restOfLines = $this->replaceSpacesService->replaceSpaces($restOfLines);

        $charactersRemaining = 256 - strlen($firstLine);

        if (strlen($restOfLines) > $charactersRemaining) {
            $restOfLines = $this->shortenService->shorten(
                $restOfLines,
                $charactersRemaining
            );
            return '<h3>' . $firstLineEscaped . '</h3>'
                . '<p class="a-c-e">'
                . $this->escapeService->escape($restOfLines)
                . '</p>';
        }

        return '<h3>' . $firstLineEscaped . '</h3>'
            . '<p>'
            . $this->escapeService->escape($restOfLines)
            . '</p>';
    }
}
