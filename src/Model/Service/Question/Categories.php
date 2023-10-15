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
        protected QuestionTable\CategoryQuestion $categoryQuestionTable,
    ) {
    }

    public function getCategories(
        QuestionEntity\Question $questionEntity
    ): array {
        if (isset($this->cache[$questionEntity->questionId])) {
            return $this->cache[$questionEntity->questionId];
        }

        $categories = [];

        $result = $this->categoryQuestionTable->select(
            columns: [
                'category_id',
            ],
            where: [
                'question_id' => $questionEntity->questionId,
            ],
            order: [
                'order'
            ],
        );

        foreach ($result as $array) {
            $categories[] = $this->fromCategoryIdFactory->buildFromCategoryId(
                $array['category_id']
            );
        }

        $this->cache[$questionEntity->questionId] = $categories;
        return $categories;
    }
}
