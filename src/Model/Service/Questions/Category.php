<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Models as QuestionModels;

class Category
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
    ) {
    }

    public function getQuestions(
        CategoryEntity\Category $categoryEntity,
        int $page = 1,
        int $questionsPerPage = 100,
        int $excludeQuestionId = null,
    ): Generator {
        $query = QuestionModels\QuestionCategories::select('question_id')
            ->where('category_id_1', $categoryEntity->categoryId);

        if ($excludeQuestionId) {
            $query->where('question_id', '!=', $excludeQuestionId);
        }

        $query->orderBy('question_created_datetime_cached', 'desc')
            ->limit($questionsPerPage)
            ->offset(($page - 1) * $questionsPerPage);

        $questionCategoriesCollection = $query->get();

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
