<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Questions\Subject;

use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class NumberOfPagesTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);

        $this->questionTable = new QuestionTable\Question(
            $this->getSql()
        );

        $this->numberOfPagesService = new QuestionService\Questions\Subject\NumberOfPages(
            $this->questionTable
        );
    }

    public function test_getNumberOfPages()
    {
        for ($iteration = 0; $iteration < 150; $iteration++) {
            $this->questionTable->insert(
                values: [
                    'subject' => 'math',
                ],
            );
        }
        for ($iteration = 0; $iteration < 50; $iteration++) {
            $this->questionTable->insert(
                values: [
                    'subject' => 'science',
                ],
            );
        }
        $this->assertSame(
            2,
            $this->numberOfPagesService->getNumberOfPages('math')
        );
        $this->assertSame(
            1,
            $this->numberOfPagesService->getNumberOfPages('science')
        );
        $this->assertSame(
            0,
            $this->numberOfPagesService->getNumberOfPages('social studies')
        );
    }
}
