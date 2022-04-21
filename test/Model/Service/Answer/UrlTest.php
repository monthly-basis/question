<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionUrlServiceMock = $this->createMock(
            QuestionService\Question\Url::class
        );
        $this->questionFromAnswerServiceMock = $this->createMock(
            QuestionService\QuestionFromAnswer::class
        );

        $this->urlService = new QuestionService\Answer\Url(
            $this->questionUrlServiceMock,
            $this->questionFromAnswerServiceMock,
        );
    }

    public function test_getUrl()
    {
        $answerEntity = (new QuestionEntity\Answer())
            ->setAnswerId(12345)
            ;
        $questionEntity = new QuestionEntity\Question();

        $this->questionFromAnswerServiceMock
            ->expects($this->once())
            ->method('getQuestionFromAnswer')
            ->with($answerEntity)
            ->willReturn($questionEntity)
        ;
        $this->questionUrlServiceMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($questionEntity)
            ->willReturn('https://www.example.com/questions/54321/question-slug')
            ;

        $this->assertSame(
            'https://www.example.com/questions/54321/question-slug#12345',
            $this->urlService->getUrl($answerEntity)
        );
    }
}
