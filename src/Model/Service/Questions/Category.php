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
        $result = $this->categoryQuestionTable->selectQuestionIdWhereCategoryId(
            categoryId: $categoryEntity->categoryId,
            limitOffset: $questionsPerPage * ($page - 1),
            limitRowCount: $questionsPerPage,
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionid(
                $array['question_id']
            );
        }
    }
}
