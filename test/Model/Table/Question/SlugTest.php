<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Question;

use MonthlyBasis\Question\Model\Db as QuestionDb;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class SlugTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new QuestionDb\Sql(
            $this->getAdapter()
        );
        $this->questionTable = new QuestionTable\Question(
            $this->sql
        );

        $this->slugTable = new QuestionTable\Question\Slug(
            $this->getAdapter(),
            $this->questionTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);
    }

    public function test_selectWhereSlug_expectedResults()
    {
        $result = $this->slugTable->selectWhereSlug('slug');
        $this->assertEmpty($result);

        $this->questionTable->insert(
            1,
            'subject',
            'message',
            'name',
            '1.2.3.4',
            'slug',
        );
        $result = $this->slugTable->selectWhereSlug('slug');
        $this->assertSame(
            [
                'name',
                'message',
                1,
                'subject',
            ],
            [
                $result->current()['created_name'],
                $result->current()['message'],
                $result->current()['question_id'],
                $result->current()['subject'],
            ]
        );
    }
}
