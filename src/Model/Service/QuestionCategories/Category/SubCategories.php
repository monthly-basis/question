<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\QuestionCategories\Category;

use Generator;
use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Category\Model\Factory as CategoryFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class SubCategories
{
    public function __construct(
        protected CategoryFactory\FromCategoryId $fromCategoryIdFactory,
        protected QuestionTable\QuestionCategories $questionCategoriesTable,
    ) {}

    public function getSubCategories(
        CategoryEntity\Category $categoryEntity
    ): Generator {
        $result = $this->questionCategoriesTable->selectSubCategoryIds(
            $categoryEntity->categoryId
        );

        foreach ($result as $row) {
            yield $this->fromCategoryIdFactory->buildFromCategoryId(
                $row['category_id_2']
            );
        }
    }
}
