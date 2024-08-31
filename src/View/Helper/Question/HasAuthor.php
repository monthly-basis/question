<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\View\Helper\Question;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class HasAuthor extends AbstractHelper
{
    public function __invoke(
        QuestionEntity\Question $questionEntity
    ): bool {
        return (
            isset($questionEntity->createdName)
            || isset($questionEntity->createdUserId)
        );
    }
}
