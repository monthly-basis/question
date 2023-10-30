<?php
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
    ): Generator {
        $result = $this->categoryQuestionTable->select(
            columns: [
                'question_id'
            ],
            where: [
                'category_id' => $categoryEntity->categoryId,
            ],
            order: 'question_views_one_month_cached DESC',
            limit: $questionsPerPage,
            offset: $questionsPerPage * ($page - 1),
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionid(
                $array['question_id']
            );
        }
    }
}
