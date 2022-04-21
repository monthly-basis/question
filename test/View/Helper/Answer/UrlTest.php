<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->urlServiceMock = $this->createMock(
            QuestionService\Answer\Url::class
        );

        $this->urlHelper = new QuestionHelper\Answer\Url(
            $this->urlServiceMock
        );
    }

    public function testInvoke()
    {
        $answerEntity = new QuestionEntity\Answer();

        $this->urlServiceMock
            ->expects($this->once())
            ->method('getUrl')
            ->with($answerEntity)
            ->willReturn('https://www.test.com/questions/123/slug#456')
            ;

        $this->assertSame(
            'https://www.test.com/questions/123/slug#456',
            $this->urlHelper->__invoke($answerEntity),
        );
    }
}
