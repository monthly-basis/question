<?php
namespace MonthlyBasis\Question\View\Helper\Question\Html;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\ContentModeration\View\Helper as ContentModerationHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\View\Helper as UserHelper;
use Throwable;

class Author extends AbstractHelper
{
    public function __construct(
        protected ContentModerationHelper\ReplaceAndEscape $replaceAndEscapeHelper,
        protected ContentModerationHelper\ReplaceAndUrlencode $replaceAndUrlencodeHelper,
        protected UserFactory\User $userFactory,
        protected UserHelper\UserHtml $userHtmlHelper,
    ) {
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string|null
    {
        try {
            $userId     = $questionEntity->getCreatedUserId();
            $userEntity = $this->userFactory->buildFromUserId($userId);
            return $this->userHtmlHelper->__invoke($userEntity);
        } catch (Throwable) {
            // Do nothing.
        }

        try {
            $createdName = $questionEntity->getCreatedName();
            $href = '/visitors?name='
                . $this->replaceAndUrlencodeHelper->__invoke($createdName);
            $innerHtml = $this->replaceAndEscapeHelper->__invoke($createdName);
            return "<a href=\"$href\" rel=\"nofollow\">$innerHtml</a>";
        } catch (Throwable) {
            // Do nothing.
        }

        return null;
    }
}
