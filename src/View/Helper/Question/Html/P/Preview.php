<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html\P;

use Error;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\View\Helper as ContentModerationHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Preview extends AbstractHelper
{
    public function __construct(
        ContentModerationHelper\StripTagsReplaceBadWordsShortenAndEscape $stripTagsReplaceBadWordsShortenAndEscapeHelper
    ) {
        $this->stripTagsReplaceBadWordsShortenAndEscapeHelper = $stripTagsReplaceBadWordsShortenAndEscapeHelper;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        try {
            $message = $questionEntity->getMessage();
        } catch (Error $error) {
            return '';
        }

        $classString = (strlen($message) > 256) ? ' class="a-c-e"' : '';

        return
            '<p' . $classString . '>'
          . $this->stripTagsReplaceBadWordsShortenAndEscapeHelper->__invoke(
                $message,
                256,
                ''
            )
          . '</p>';
    }
}
