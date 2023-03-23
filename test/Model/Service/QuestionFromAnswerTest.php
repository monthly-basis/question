<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class QuestionFromAnswerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );
        $this->questionFromAnswerService = new QuestionService\QuestionFromAnswer(
            $this->fromQuestionIdFactoryMock
        );
    }

    public function testGetQuestionFromAnswer()
    {
        $answerEntity = (new QuestionEntity\Answer())
            ->setQuestionId(123);
        $questionEntity = new QuestionEntity\Question();

        $this->fromQuestionIdFactoryMock
             ->expects($this->once())
             ->method('buildFromQuestionId')
             ->with(123)
             ->willReturn($questionEntity);
        $this->assertSame(
            $questionEntity,
            $this->questionFromAnswerService->getQuestionFromAnswer(
                $answerEntity
            ),
        );
    }
}
