<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionSearchSimilarTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->questionTable = new QuestionTable\Question(
            $this->getSql()
        );

        $this->questionSearchSimilarTable = new QuestionTable\QuestionSearchSimilar(
            $this->getSql(),
            $this->getAdapter(),
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['question', 'question_search_similar']);
        $this->setForeignKeyChecks(1);
    }

    public function test_rotate()
    {
        $this->questionTable->insert(
            values: [
                'message' => 'message of moved question with 0 answers',
                'answer_count_cached' => 0,
                'moved_datetime' => '2022-10-31 13:56:24',
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of question with 1 answer',
                'answer_count_cached' => 0,
            ]
        );
        $this->questionTable->insert(
            values: [
                'message' => 'message of deleted question with 100 answers',
                'answer_count_cached' => 100,
            ]
        );
        $this->questionSearchSimilarTable->rotate();

        $result = $this->questionSearchSimilarTable->select(
            columns: [
                'count' => new \Laminas\Db\Sql\Expression('COUNT(*)')
            ],
        );
        $this->assertSame(
            [
                'count' => 1,
            ],
            $result->current(),
        );
    }

    public function test_selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals()
    {
        $result = $this->questionSearchSimilarTable
            ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                'the search query',
                12345,
                0,
                10,
            );
        $this->assertEmpty(
            iterator_to_array($result)
        );
    }
}
