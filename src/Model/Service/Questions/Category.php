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
    ): Generator {
        $result = $this->categoryQuestionTable->selectQuestionIdWhereCategoryId(
            $categoryEntity->categoryId
        );

        foreach ($result as $array) {
            yield $this->fromQuestionIdFactory->buildFromQuestionid(
                $array['question_id']
            );
        }
    }
}
