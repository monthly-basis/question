<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Preview extends AbstractHelper
{
    public function __construct(
        protected ContentModerationService\Replace\BadWords $replaceBadWordsService,
        protected ContentModerationService\Replace\LineBreaks $replaceLineBreaksService,
        protected ContentModerationService\Replace\Spaces $replaceSpacesService,
        protected QuestionService\Question\RootRelativeUrl $rootRelativeUrlService,
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

        $rru = $this->rootRelativeUrlService->getRootRelativeUrl(
            $questionEntity
        );

        $message = $this->replaceBadWordsService->replaceBadWords($message, '');
        $message = $this->replaceLineBreaksService->replaceLineBreaks($message);
        $message = trim($message);

        $lines     = preg_split('/\n/', $message);
        $firstLine = $lines[0];
        $firstLine = $this->replaceSpacesService->replaceSpaces($firstLine);

        $firstLineEscaped = $this->escapeService->escape($firstLine);

        if (strlen($firstLine) > 128) {
            $firstLine = $this->shortenService->shorten(
                $firstLine,
                128
            );
            $firstLineEscaped = $this->escapeService->escape($firstLine);
            return "<a href=\"$rru\" class=\"a-c-e\">$firstLineEscaped</a>";
        }

        if (count($lines) == 1) {
            return "<a href=\"$rru\">$firstLineEscaped</a>";
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
            return "<a href=\"$rru\">$firstLineEscaped</a><br>"
                . '<span class="a-c-e">'
                . $this->escapeService->escape($restOfLines)
                . '</span>';
        }

        return "<a href=\"$rru\">$firstLineEscaped</a><br>"
            . '<span>'
            . $this->escapeService->escape($restOfLines)
            . '</span>';
    }
}
