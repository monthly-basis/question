<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class IncrementViewsTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->incrementViewsService = new QuestionService\Question\IncrementViews(
            $this->questionTableMock
        );
    }

    public function testIncrementViews()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123);

        $this->questionTableMock
             ->expects($this->once())
             ->method('updateViewsWhereQuestionId')
             ->willReturn(true);

        $this->assertTrue(
            $this->incrementViewsService->incrementViews($questionEntity)
        );
    }
}
