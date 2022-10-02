<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\Question\Views\Increment;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;

class Conditionally
{
    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionService\Question\IncrementViews $incrementViewsService,
    ) {}

    public function conditionallyIncrementViews(
        QuestionEntity\Question $questionEntity
    ) {
        $key = $questionEntity->getQuestionId() . '-' . $_SERVER['REMOTE_ADDR'];

        if ($this->memcachedService->get($key)) {
            return;
        }

        $this->memcachedService->setForMinutes(
            key: $key,
            value: true,
            minutes: 1
        );
        $this->incrementViewsService->incrementViews($questionEntity);
    }
}
