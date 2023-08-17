<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class CategoryQuestionTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('category_question');

        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->categoryQuestionTable = new QuestionTable\CategoryQuestion(
            $this->sql
        );
    }

    public function test_insert_result()
    {
        $this->setForeignKeyChecks(0);
        $result = $this->categoryQuestionTable->insert([
            'category_id' => 123,
            'question_id' => 123,
            'order'       => 0,
        ]);
        $this->assertSame(1, $result->getAffectedRows());
        $this->setForeignKeyChecks(1);
    }

    public function test_selectQuestionIdWhereCategoryId()
    {
        $result = $this->categoryQuestionTable->selectQuestionIdWhereCategoryId(
            123
        );
        $this->assertEmpty($result);
    }

    public function test_selectCountWhereCategoryId()
    {
        $result = $this->categoryQuestionTable->selectCountWhereCategoryId(123);
        $this->assertSame(
            0,
            $result->current()['COUNT(*)']
        );
    }
}
