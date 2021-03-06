<?php
namespace MonthlyBasis\StringTest\View\Helper;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use PHPUnit\Framework\TestCase;

class LinkToQuestionHtmlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->rootRelativeUrlServiceMock = $this->createMock(
            QuestionService\Question\RootRelativeUrl::class
        );
        $this->linkToQuestionHtmlHelper = new QuestionHelper\Question\Subject\LinkToQuestionHtml(
            new ContentModerationService\Replace\Spaces(),
            $this->rootRelativeUrlServiceMock,
            new StringService\Escape()
        );
    }

    public function testInvoke()
    {
        $this->rootRelativeUrlServiceMock->method('getRootRelativeUrl')->will(
            $this->onConsecutiveCalls(
                '/questions/123/awesome',
                '/questions/456/fantastic'
            )
        );
        $questionEntity = new QuestionEntity\Question();
        $questionEntity->setSubject('mathematics');

        $html = $this->linkToQuestionHtmlHelper->__invoke(
            $questionEntity
        );

        $this->assertSame(
            '<a href="/questions/123/awesome">mathematics</a>',
            $html
        );

        $questionEntity->setSubject('  lots     of    spaces   !!!!!    ');

        $html = $this->linkToQuestionHtmlHelper->__invoke(
            $questionEntity
        );

        $this->assertSame(
            '<a href="/questions/456/fantastic">lots of spaces !!!!!</a>',
            $html
        );
    }
}
