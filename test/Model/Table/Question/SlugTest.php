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
            values: [
                'created_name' => 'created name',
                'created_ip'   => '1.2.3.4',
                'message'      => 'message',
                'slug'         => 'slug',
            ]
        );
        $result = $this->slugTable->selectWhereSlug('slug');
        $this->assertSame(
            [
                'created name',
                '1.2.3.4',
                'message',
                1,
            ],
            [
                $result->current()['created_name'],
                $result->current()['created_ip'],
                $result->current()['message'],
                $result->current()['question_id'],
            ]
        );
    }
}
