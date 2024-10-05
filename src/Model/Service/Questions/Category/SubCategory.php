<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\Questions\Category;

use Generator;
use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Models as QuestionModels;

class SubCategory
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
    ) {
    }

    public function getQuestions(
        CategoryEntity\Category $categoryEntity,
        CategoryEntity\Category $subCategoryEntity,
    ): Generator {
        $questionCategoriesCollection = QuestionModels\QuestionCategories::select('question_id')
            ->where('category_id_1', $categoryEntity->categoryId)
            ->where('category_id_2', $subCategoryEntity->categoryId)
            ->orderBy('question_created_datetime_cached')
            ->take(100)
            ->get();

        foreach ($questionCategoriesCollection as $questionCategories) {
            $questionEntity = $this->fromQuestionIdFactory->buildFromQuestionid(
                $questionCategories->question_id
            );

            if (
                isset($questionEntity->deletedDateTime)
                || isset($questionEntity->movedDateTime)
            ) {
                continue;
            }

            yield $questionEntity;
        }
    }
}
