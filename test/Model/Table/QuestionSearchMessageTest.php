<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionSearchMessageTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->questionTable = new QuestionTable\Question(
            $this->getSql()
        );

        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->questionSearchMessageTable = new QuestionTable\QuestionSearchMessage(
            $this->memcachedServiceMock,
            $this->getSql(),
            $this->getAdapter(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['question', 'question_search_message']);
        $this->setForeignKeyChecks(1);
    }

    public function test_rotate()
    {
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 0 views',
                'views_not_bot_one_month' => 0,
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 1 view',
                'views_not_bot_one_month' => 1,
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 100 views',
                'views_not_bot_one_month' => 100,
            ]
        );
        $this->questionSearchMessageTable->rotate();

        $result = $this->questionSearchMessageTable->select(
            columns: [
                'count' => new \Laminas\Db\Sql\Expression('COUNT(*)')
            ],
        );
        $this->assertSame(
            [
                'count' => 2,
            ],
            $result->current(),
        );
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
                12345,
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
