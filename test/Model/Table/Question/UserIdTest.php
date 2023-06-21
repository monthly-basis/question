<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class UserIdTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userIdTable = new QuestionTable\Question\UserId(
            $this->getAdapter(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);
    }

    public function test_selectUserIdOrderByMaxCreatedDatetime()
    {
        $result = $this->userIdTable->selectUserIdOrderByMaxCreatedDatetime();
        $this->assertEmpty($result);
    }
}
