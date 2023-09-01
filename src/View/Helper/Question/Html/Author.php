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
        if (isset($questionEntity->createdUserId)) {
            $userEntity = $this->userFactory->buildFromUserId(
                $questionEntity->createdUserId
            );
            return $this->userHtmlHelper->__invoke($userEntity);
        }

        if (isset($questionEntity->createdName)) {
            $createdName = $questionEntity->getCreatedName();

            $createdNameReplacedAndEncoded = $this->replaceAndUrlencodeHelper->__invoke(
                $createdName
            );
            $createdNameReplacedAndEscaped = $this->replaceAndEscapeHelper->__invoke(
                $createdName
            );

            $href = '/visitors?name=' . $createdNameReplacedAndEncoded;
            return "<a href=\"$href\" rel=\"nofollow\">$createdNameReplacedAndEscaped</a>";
        }

        return null;
    }
}
