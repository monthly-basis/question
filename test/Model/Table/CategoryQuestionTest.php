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
        $result = $this->categoryQuestionTable->insert([
            'category_id' => 123,
            'question_id' => 123,
            'order'       => 0,
        ]);
        $this->assertSame(1, $result->getAffectedRows());
    }
}
