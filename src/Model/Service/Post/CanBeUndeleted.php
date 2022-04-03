<?php
namespace MonthlyBasis\Question\Model\Service\Post;

use Error;
use MonthlyBasis\Group\Model\Service as GroupService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Service as UserService;

class CanBeUndeleted
{
    public function __construct(
        GroupService\LoggedInUserInGroupName $loggedInUserInGroupNameService,
        StringService\Contains\CaseInsensitive $caseInsensitiveService,
        UserService\LoggedIn $loggedInService
    ) {
        $this->loggedInUserInGroupNameService = $loggedInUserInGroupNameService;
        $this->caseInsensitiveService         = $caseInsensitiveService;
        $this->loggedInService                = $loggedInService;
    }

    public function canBeUndeleted(QuestionEntity\Post $postEntity): bool
    {
        try {
            $deletedReason = $postEntity->getDeletedReason();
        } catch (Error $error) {
            return false;
        }

        if (!$this->loggedInService->isLoggedIn()) {
            return false;
        }

        if ($this->loggedInUserInGroupNameService->isLoggedInUserInGroupName('Webmaster')) {
            return true;
        }

        if (!$this->loggedInUserInGroupNameService->isLoggedInUserInGroupName('Admin')) {
            return false;
        }

        if (
            $this->caseInsensitiveService->caseInsensitive($deletedReason, 'dmca')
            || $this->caseInsensitiveService->caseInsensitive($deletedReason, 'pearson')
            || $this->caseInsensitiveService->caseInsensitive($deletedReason, 'connections')
        ) {
            return false;
        }

        return true;
    }
}
