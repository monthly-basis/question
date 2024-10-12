<?php

declare(strict_types=1);

namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class QuestionCategoriesTest extends TableTestCase
{
    protected QuestionTable\QuestionCategories $questionCategoriesTable;

    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question_categories');
        $this->setForeignKeyChecks(1);

        $this->questionCategoriesTable = new QuestionTable\QuestionCategories(
            $this->getSql()
        );
    }

    public function test_selectSubCategoryIds()
    {
        $result = $this->questionCategoriesTable->selectSubCategoryIds(1);
        $this->assertEmpty($result);
    }
}
