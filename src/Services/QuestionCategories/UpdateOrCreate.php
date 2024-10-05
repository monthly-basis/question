<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Services\QuestionCategories;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Models as QuestionModels;

class UpdateOrCreate
{
    public function __construct(
        protected QuestionService\Question\Categories $categoriesService,
    ) {}

    public function updateOrCreate(
        QuestionEntity\Question $questionEntity
    ): false|QuestionModels\QuestionCategories {
        $categories = $this->categoriesService->getCategories($questionEntity);

        if (empty($categories)) {
            return false;
        }

        return QuestionModels\QuestionCategories::updateOrCreate(
            [
                'question_id' => $questionEntity->questionId,
            ],
            [
                'category_id_1' => $categories[0]->categoryId,
                'category_id_2' => $categories[1]->categoryId ?? null,
                'category_id_3' => $categories[2]->categoryId ?? null,
                'question_created_datetime_cached' => $questionEntity->createdDateTime,
            ]
        );
    }
}
