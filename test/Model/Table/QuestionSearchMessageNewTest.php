<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionSearchMessageNewTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->questionSearchMessageNewTable = new QuestionTable\QuestionSearchMessageNew(
            $this->getSql(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question_search_message_new');
        $this->setForeignKeyChecks(1);
    }

    public function test_createTableQuestionSearchMessageNew()
    {
        $this->dropTable('question_search_message_new');
        $result = $this->questionSearchMessageNewTable->createTableQuestionSearchMessageNew();
        $this->assertInstanceOf(
            Result::class,
            $result
        );
    }
}
