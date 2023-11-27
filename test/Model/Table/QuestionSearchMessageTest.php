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
        $this->questionSearchMessageNewTable = new QuestionTable\QuestionSearchMessageNew(
            $this->getSql(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['question', 'question_search_message']);
        $this->setForeignKeyChecks(1);
    }

    public function test_rotate()
    {
        $this->dropAndCreateTable('question_search_message_new');
        $this->questionSearchMessageNewTable->insert(
            values: [
                'question_id' => 1,
                'message'     => 'message 1',
            ]
        );
        $this->questionSearchMessageNewTable->insert(
            values: [
                'question_id' => 2,
                'message'     => 'message 2',
            ]
        );
        $this->questionSearchMessageNewTable->insert(
            values: [
                'question_id' => 3,
                'message'     => 'message 3',
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
                'count' => 3,
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
                query: 'the search query',
                innerLimitOffset: 0,
                innerLimitRowCount: 100,
                outerLimitOffset: 0,
                outerLimitRowCount: 10,
                questionIdNotIn: [1, 2, 3],
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
