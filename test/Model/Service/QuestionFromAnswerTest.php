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

    public function test_getQuestionFromAnswer()
    {
        $answerEntity             = new QuestionEntity\Answer();
        $answerEntity->answerId   = 123;
        $answerEntity->questionId = 456;

        $questionEntity = new QuestionEntity\Question();

        $this->fromQuestionIdFactoryMock
             // Method gets called once even though service is called twice.
             ->expects($this->once())
             ->method('buildFromQuestionId')
             ->with(456)
             ->willReturn($questionEntity);

        // Call service for the first time. Factory gets called.
        $this->assertSame(
            $questionEntity,
            $this->questionFromAnswerService->getQuestionFromAnswer(
                $answerEntity
            ),
        );

        /*
         * Call service again. This time, question factory does not get called
         * because question is returned from cache instead.
         */
        $this->assertSame(
            $questionEntity,
            $this->questionFromAnswerService->getQuestionFromAnswer(
                $answerEntity
            ),
        );

		$reflectionClass = new \ReflectionClass($this->questionFromAnswerService);
		$reflectionProperty = $reflectionClass->getProperty('cache');
        $this->assertSame(
            [
                123 => $questionEntity,
            ],
            $reflectionProperty->getValue($this->questionFromAnswerService)
        );
    }
}
