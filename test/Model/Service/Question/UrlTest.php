<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->rootRelativeUrlServiceMock = $this->createMock(
            QuestionService\Question\RootRelativeUrl::class
        );

        $this->urlService = new QuestionService\Question\Url(
            $this->rootRelativeUrlServiceMock
        );
    }

    public function test_getUrl_includeQuestionsDirectory_expecteString()
    {
        $_SERVER['HTTP_HOST'] = 'www.test.com';
        $questionEntity = new QuestionEntity\Question();

        $this->rootRelativeUrlServiceMock
            ->expects($this->once())
            ->method('getRootRelativeUrl')
            ->with($questionEntity, true)
            ->willReturn('/questions/12345/My-Question-Title')
            ;

        $this->assertSame(
            'https://www.test.com/questions/12345/My-Question-Title',
            $this->urlService->getUrl($questionEntity)
        );
    }

    public function test_getUrl_excludeQuestionsDirectory_expecteString()
    {
        $_SERVER['HTTP_HOST'] = 'www.test.com';
        $questionEntity = new QuestionEntity\Question();

        $this->rootRelativeUrlServiceMock
            ->expects($this->once())
            ->method('getRootRelativeUrl')
            ->with($questionEntity, false)
            ->willReturn('/12345/My-Question-Title')
            ;

        $this->assertSame(
            'https://www.test.com/12345/My-Question-Title',
            $this->urlService->getUrl($questionEntity, false)
        );
    }
}
