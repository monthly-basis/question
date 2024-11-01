<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Category
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\CategoryQuestion $categoryQuestionTable,
    ) {
    }

    public function getQuestions(
        CategoryEntity\Category $categoryEntity,
        int $page = 1,
        int $questionsPerPage = 100,
        int $excludeQuestionId = null,
    ): Generator {
        $where = [
            'category_id' => $categoryEntity->categoryId,
        ];
        if ($excludeQuestionId) {
            $where['question_id != ?'] = $excludeQuestionId;
        }

        $result = $this->categoryQuestionTable->select(
            columns: [
                'question_id'
            ],
            where: $where,
            order: 'question_created_datetime_cached DESC',
            limit: $questionsPerPage,
            offset: $questionsPerPage * ($page - 1),
        );

        foreach ($result as $array) {
            $questionEntity = $this->fromQuestionIdFactory->buildFromQuestionid(
                $array['question_id']
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
