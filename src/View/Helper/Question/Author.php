<?php
namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Exception as QuestionException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use Throwable;

class Author extends AbstractHelper
{
    public function __construct(
        protected UserFactory\User $userFactory,
        protected UserService\DisplayNameOrUsername $displayNameOrUsernameService,
    ) {
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        try {
            $userId     = $questionEntity->getCreatedUserId();
            $userEntity = $this->userFactory->buildFromUserId($userId);
            return $this->displayNameOrUsernameService->getDisplayNameOrUsername(
                $userEntity
            );
        } catch (Throwable) {
            // Do nothing.
        }

        try {
            return $questionEntity->getCreatedName();
        } catch (Throwable) {
            // Do nothing.
        }

        throw new QuestionException('Could not get author of question.');
    }
}
