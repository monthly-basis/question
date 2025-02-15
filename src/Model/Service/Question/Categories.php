<?php
namespace MonthlyBasis\Question\Model\Service\Question;

use MonthlyBasis\Category\Model\Factory as CategoryFactory;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Categories
{
    protected array $cache;

    public function __construct(
        protected CategoryFactory\FromCategoryId $fromCategoryIdFactory,
        protected QuestionTable\QuestionCategories $questionCategoriesTable,
    ) {
    }

    public function getCategories(
        QuestionEntity\Question $questionEntity
    ): array {
        if (isset($this->cache[$questionEntity->questionId])) {
            return $this->cache[$questionEntity->questionId];
        }

        $categories = [];

        $result = $this->questionCategoriesTable->selectWhereQuestionId(
            $questionEntity->questionId,
        );

        foreach ($result as $array) {
            $categoryEntity = $this->fromCategoryIdFactory->buildFromCategoryId(
                $array['category_id']
            );
            if (!$categoryEntity->active) {
                continue;
            }
            $categories[] = $categoryEntity;
        }

        $this->cache[$questionEntity->questionId] = $categories;
        return $categories;
    }
}
