<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class H2Test extends TestCase
{
    protected function setUp(): void
    {
        $this->escapeServiceMock = $this->createMock(
            StringService\Escape::class
        );

        $this->h2Helper = new QuestionHelper\Question\Html\H2(
            $this->escapeServiceMock
        );
    }

    public function test___invoke_headlineIsSet_h2TagWithEscapedHeadline()
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
            '<h2>The Headline Escaped</h2>',
            $this->h2Helper->__invoke($questionEntity)
        );
    }

    public function test___invoke_headlineIsNotSet_h2TagWithEscapedSubject()
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
            '<h2>The Subject Escaped</h2>',
            $this->h2Helper->__invoke($questionEntity)
        );
    }
}
