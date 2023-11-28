<?php
namespace MonthlyBasis\Question\View\Helper\Answer;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;

class AuthorOrNull extends AbstractHelper
{
    public function __construct(
        protected UserFactory\User $userFactory,
        protected UserService\DisplayNameOrUsername $displayNameOrUsernameService,
    ) {
    }

    public function __invoke(
        QuestionEntity\Answer $answerEntity
    ): string|null {
        if (isset($answerEntity->createdUserId)) {
            $userEntity = $this->userFactory->buildFromUserId(
                $answerEntity->createdUserId
            );
            return $this->displayNameOrUsernameService->getDisplayNameOrUsername(
                $userEntity
            );
        }

        return isset($answerEntity->createdName)
            ? $answerEntity->createdName
            : null;
    }
}
