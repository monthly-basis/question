<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->rootRelativeUrlServiceMock = $this->createMock(
            QuestionService\Answer\RootRelativeUrl::class
        );

        $this->urlService = new QuestionService\Answer\Url(
            $this->rootRelativeUrlServiceMock,
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getUrl()
    {
        $_SERVER = [
            'HTTP_HOST' => 'www.example.com',
        ];

        $answerEntity = (new QuestionEntity\Answer())
            ->setAnswerId(12345)
            ;

        $this->rootRelativeUrlServiceMock
            ->expects($this->once())
            ->method('getRootRelativeUrl')
            ->with($answerEntity)
            ->willReturn('/answers/12345/answer-slug')
        ;

        $this->assertSame(
            'https://www.example.com/answers/12345/answer-slug',
            $this->urlService->getUrl($answerEntity)
        );
    }
}
