<?php
namespace MonthlyBasis\Question\Model\Service\Questions\Category;

use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Count
{
    public function __construct(
        protected QuestionTable\CategoryQuestion $categoryQuestionTable,
    ) {
    }

    public function getCount(
        CategoryEntity\Category $categoryEntity,
    ): int {
        $result = $this->categoryQuestionTable->selectCountWhereCategoryId(
            $categoryEntity->categoryId,
        );

        return intval($result->current()['COUNT(*)']);
    }
}
