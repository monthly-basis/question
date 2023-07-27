<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class AnswerSearchMessageTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->answerSearchMessageTable = new QuestionTable\AnswerSearchMessage(
            $this->getSql(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer_search_message');
        $this->setForeignKeyChecks(1);
    }

    public function test_rotate()
    {
        $result = $this->answerSearchMessageTable->rotate();
        $this->assertEmpty($result);
    }

    public function test_selectAnswerIdWhereMatchMessageAgainstAndAnswerIdNotEquals()
    {
        $result = $this->answerSearchMessageTable
            ->selectAnswerIdWhereMatchMessageAgainstAndAnswerIdNotEquals(
                'the search query',
                123,
                0,
                100,
            );
        $this->assertEmpty(
            iterator_to_array($result)
        );
    }
}
