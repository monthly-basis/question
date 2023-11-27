<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
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

        $this->dropAndCreateTable('question_search_message_new');
    }

    public function test_createLikeQuestionSearchMessage()
    {
        $this->dropTable('question_search_message_new');
        $result = $this->questionSearchMessageNewTable->createLikeQuestionSearchMessage();
        $this->assertInstanceOf(
            Result::class,
            $result
        );
    }

    public function test_dropTableIfExists()
    {
        try {
            $result = $this->questionSearchMessageNewTable->createLikeQuestionSearchMessage();
            $this->fail();
        } catch (InvalidQueryException $invalidQueryException) {
            $this->assertSame(
                'Statement could not be executed',
                substr($invalidQueryException->getMessage(), 0, 31)
            );
        }
        $this->questionSearchMessageNewTable->dropTableIfExists();
        $this->questionSearchMessageNewTable->createLikeQuestionSearchMessage();
    }
}
