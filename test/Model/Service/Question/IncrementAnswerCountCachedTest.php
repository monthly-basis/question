<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class IncrementAnswerCountCachedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionIdTableMock = $this->createMock(
            QuestionTable\Question\QuestionId::class
        );

        $this->incrementAnswerCountCachedService = new QuestionService\Question\IncrementAnswerCountCached(
            $this->questionIdTableMock
        );
    }

    public function test_incrementAnswerCountCached_affectedRows0_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123);
        $resultMock = $this->createMock(
            Result::class
        );

        $this->questionIdTableMock
             ->expects($this->once())
             ->method('updateAnswerCountCachedWhereQuestionId')
             ->with(123)
             ->willReturn($resultMock);
        $resultMock
             ->expects($this->once())
             ->method('getAffectedRows')
             ->willReturn(0);

        $this->assertFalse(
            $this->incrementAnswerCountCachedService->incrementAnswerCountCached($questionEntity)
        );
    }

    public function test_incrementAnswerCountCached_affectedRows1_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123);
        $resultMock = $this->createMock(
            Result::class
        );

        $this->questionIdTableMock
             ->expects($this->once())
             ->method('updateAnswerCountCachedWhereQuestionId')
             ->with(123)
             ->willReturn($resultMock);
        $resultMock
             ->expects($this->once())
             ->method('getAffectedRows')
             ->willReturn(1);

        $this->assertTrue(
            $this->incrementAnswerCountCachedService->incrementAnswerCountCached($questionEntity)
        );
    }
}
