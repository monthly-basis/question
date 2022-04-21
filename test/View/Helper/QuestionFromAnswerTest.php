<?php
namespace MonthlyBasis\QuestionTest\View\Helper;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class QuestionFromAnswerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFromAnswerServiceMock = $this->createMock(
            QuestionService\QuestionFromAnswer::class
        );

        $this->questionFromAnswerHelper = new QuestionHelper\QuestionFromAnswer(
            $this->questionFromAnswerServiceMock
        );
    }

    public function test___invoke()
    {
        $questionEntity = new QuestionEntity\Question();
        $answerEntity   = new QuestionEntity\Answer();

        $this->questionFromAnswerServiceMock
            ->expects($this->once())
            ->method('getQuestionFromAnswer')
            ->with($answerEntity)
            ->willReturn($questionEntity)
            ;

        $this->assertSame(
            $questionEntity,
            $this->questionFromAnswerHelper->__invoke($answerEntity)
        );
    }
}
