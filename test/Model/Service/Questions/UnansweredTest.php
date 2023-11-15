<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Questions\Subject;

use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class UnansweredTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('question');
        $this->setForeignKeyChecks(1);

        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );
        $this->questionTable = new QuestionTable\Question(
            $this->getSql()
        );

        $this->unansweredService = new QuestionService\Questions\Unanswered(
            $this->fromQuestionIdFactoryMock,
            $this->questionTable
        );
    }

    public function test_getUnansweredQuestions()
    {
        $this->questionTable->insert(
            values: [
                'answer_count_cached'     => 0,
                'views_one_month' => 99,
            ]
        );
        $this->questionTable->insert(
            values: [
                'answer_count_cached' => 99,
            ]
        );
        $this->questionTable->insert(
            values: [
                'answer_count_cached'     => 0,
                'views_one_month' => 3,
            ]
        );

        $this->fromQuestionIdFactoryMock
            ->expects($this->exactly(2))
            ->method('buildFromQuestionId')
        ;

        $generator = $this->unansweredService->getUnansweredQuestions();
        iterator_to_array($generator);

    }
}
