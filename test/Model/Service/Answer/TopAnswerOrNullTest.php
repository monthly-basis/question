<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class TopAnswerOrNullTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answersServiceMock = $this->createMock(
            QuestionService\Answer\Answers::class
        );

        $this->topAnswerOrNullService = new QuestionService\Answer\TopAnswerOrNull(
            $this->answersServiceMock,
        );
    }

    public function test_getTopAnswerOrNull_noAnswers_null()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->answersServiceMock
             ->expects($this->once())
             ->method('getAnswers')
             ->with($questionEntity)
             ->willReturn([])
             ;

        $this->assertNull(
            $this->topAnswerOrNullService->getTopAnswerOrNull($questionEntity)
        );
    }

    public function test_getTopAnswerOrNull_answersWith0Rating_null()
    {
        $questionEntity = new QuestionEntity\Question();

        $answerEntity1 = new QuestionEntity\Answer();
        $answerEntity1->rating = 0;

        $answerEntity2 = new QuestionEntity\Answer();
        $answerEntity2->rating = 0;

        $this->answersServiceMock
             ->expects($this->once())
             ->method('getAnswers')
             ->with($questionEntity)
             ->willReturn([$answerEntity1, $answerEntity2])
             ;

        $this->assertNull(
            $this->topAnswerOrNullService->getTopAnswerOrNull($questionEntity)
        );
    }

    public function test_getTopAnswerOrNull_answersWithRating_answerEntity()
    {
        $questionEntity = new QuestionEntity\Question();

        $answerEntity1 = new QuestionEntity\Answer();
        $answerEntity1->rating = 1;

        $answerEntity2 = new QuestionEntity\Answer();
        $answerEntity2->answerId = 2;
        $answerEntity2->rating = 2;
        $answerEntity2->createdDateTime = new DateTime('2020-01-01 00:00:00');

        $answerEntity3 = new QuestionEntity\Answer();
        $answerEntity3->answerId = 3;
        $answerEntity3->rating = 2;
        $answerEntity3->createdDateTime = new DateTime('2022-01-01 00:00:00');

        $this->answersServiceMock
             ->expects($this->once())
             ->method('getAnswers')
             ->with($questionEntity)
             ->willReturn([$answerEntity1, $answerEntity2, $answerEntity3])
             ;

        $this->assertSame(
            $answerEntity2,
            $this->topAnswerOrNullService->getTopAnswerOrNull($questionEntity)
        );
    }

    public function test_getTopAnswerOrNull_0Rating0MinimumRating_answerEntity()
    {
        $questionEntity = new QuestionEntity\Question();

        $answerEntity1 = new QuestionEntity\Answer();
        $answerEntity1->rating = 0;
        $answerEntity1->createdDateTime = new DateTime('2022-01-01 00:00:00');

        $answerEntity2 = new QuestionEntity\Answer();
        $answerEntity2->rating = 0;
        $answerEntity2->createdDateTime = new DateTime('2020-01-01 00:00:00');

        $this->answersServiceMock
             ->expects($this->once())
             ->method('getAnswers')
             ->with($questionEntity)
             ->willReturn([$answerEntity1, $answerEntity2])
             ;

        $this->assertSame(
            $answerEntity2,
            $this->topAnswerOrNullService->getTopAnswerOrNull(
                questionEntity: $questionEntity,
                minimumRating: 0,
            )
        );
    }
}
