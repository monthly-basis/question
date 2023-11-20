<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
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
                'views_one_month' => 0,
                'moved_datetime' => '2022-10-31 13:56:24',
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 1 view',
                'views_one_month' => 0,
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 100 views',
                'views_one_month' => 100,
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

    public function test_select()
    {
        $this->questionSearchMessageTable->insert(
            values: [
                'question_id' => 1,
                'message'     => 'message 1',
            ]
        );
        $this->questionSearchMessageTable->insert(
            values: [
                'question_id' => 2,
                'message'     => 'message 2',
            ]
        );
        $result = $this->questionSearchMessageTable->select(
            columns: [
                'max' => new \Laminas\Db\Sql\Expression('MAX(`question_search_message_id`)')
            ],
        );
        $this->assertSame(
            [
                'max' => 2,
            ],
            $result->current()
        );

        $result = $this->questionSearchMessageTable->select(
            columns: [
                'question_id',
                'message',
            ],
            where: [
                'question_search_message_id' => 2,
            ],
        );
        $this->assertSame(
            [
                'question_id' => 2,
                'message'     => 'message 2',
            ],
            $result->current()
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
