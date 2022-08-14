<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionSearchMessageTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->questionSearchMessageTable = new QuestionTable\QuestionSearchMessage(
            $this->memcachedServiceMock,
            $this->getAdapter()
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question_search_message');
        $this->setForeignKeyChecks(1);
    }

    public function test_selectQuestionIdWhereMatchAgainstOrderByScoreDesc()
    {
        $result = $this->questionSearchMessageTable
            ->selectQuestionIdWhereMatchAgainstOrderByScoreDesc(
                'the search query',
                0,
                100,
            );
        $this->assertEmpty(
            iterator_to_array($result)
        );
    }

    public function test_selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc()
    {
        $result = $this->questionSearchMessageTable
            ->selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
                'the search query',
                0,
                100,
                0,
                100
            );
        $this->assertEmpty(
            iterator_to_array($result)
        );
    }

    public function test_selectCountWhereMatchMessageAgainst()
    {
        $result = $this->questionSearchMessageTable
            ->selectCountWhereMatchMessageAgainst(
                'the search query'
            );
        $this->assertSame(
            [
                'COUNT(*)' => 0,
            ],
            $result->current()
        );
    }
}
