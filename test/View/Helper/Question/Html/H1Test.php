<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class H1Test extends TestCase
{
    protected function setUp(): void
    {
        $this->escapeServiceMock = $this->createMock(
            StringService\Escape::class
        );

        $this->h1Helper = new QuestionHelper\Question\Html\H1(
            $this->escapeServiceMock
        );
    }

    public function test___invoke_headlineIsSet_h1TagWithEscapedHeadline()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('The Headline')
            ;

        $this->escapeServiceMock
            ->expects($this->once())
            ->method('escape')
            ->with('The Headline')
            ->willReturn('The Headline Escaped')
            ;

        $this->assertSame(
            '<h1>The Headline Escaped</h1>',
            $this->h1Helper->__invoke($questionEntity)
        );
    }

    public function test___invoke_headlineIsNotSet_h1TagWithEscapedSubject()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setSubject('The Subject')
            ;

        $this->escapeServiceMock
            ->expects($this->once())
            ->method('escape')
            ->with('The Subject')
            ->willReturn('The Subject Escaped')
            ;

        $this->assertSame(
            '<h1>The Subject Escaped</h1>',
            $this->h1Helper->__invoke($questionEntity)
        );
    }
}
