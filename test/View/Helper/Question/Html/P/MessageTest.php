<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html\P;

use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    protected function setUp(): void
    {
        $this->toHtmlServiceMock = $this->createMock(
            ContentModerationService\ToHtml::class
        );

        $this->messageHelper = new QuestionHelper\Question\Html\P\Message(
            $this->toHtmlServiceMock
        );
    }

    public function test___invoke_messageIsNotSet_emptyString()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->toHtmlServiceMock
            ->expects($this->exactly(0))
            ->method('toHtml')
            ;

        $this->assertSame(
            '',
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageIsSet_pTagWithInnerHtml()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;

        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with('The message.')
            ->willReturn('Result string from <b>toHtml</b> service.')
            ;

        $this->assertSame(
            '<p class="message">Result string from <b>toHtml</b> service.</p>',
            $this->messageHelper->__invoke($questionEntity),
        );
    }
}
