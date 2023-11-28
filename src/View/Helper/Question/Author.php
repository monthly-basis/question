<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;

/**
 * @deprecated Use Question\Helper\Question\AuthorOrNull instead
 */
class Author extends AbstractHelper
{
    public function __construct(
        protected UserFactory\User $userFactory,
        protected UserService\DisplayNameOrUsername $displayNameOrUsernameService,
    ) {
    }

    public function __invoke(
        QuestionEntity\Question $questionEntity
    ): string|null {
        if (isset($questionEntity->createdUserId)) {
            $userEntity = $this->userFactory->buildFromUserId(
                $questionEntity->createdUserId
            );
            return $this->displayNameOrUsernameService->getDisplayNameOrUsername(
                $userEntity
            );
        }

        return isset($questionEntity->createdName)
            ? $questionEntity->createdName
            : null;
    }
}
