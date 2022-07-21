<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html;

use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\String\Model\Service as StringService;
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

        $this->messageHelper = new QuestionHelper\Question\Html\Message(
            $this->toHtmlServiceMock,
        );
    }

    public function test___invoke_messageIsNotSet_emptyString()
    {
        $this->toHtmlServiceMock
            ->expects($this->exactly(0))
            ->method('toHtml')
            ;

        $this->assertSame(
            '',
            $this->messageHelper->__invoke(new QuestionEntity\Question()),
        );
    }

    public function test___invoke_messageHtmlIs0Length_expectedString()
    {
        $messageHtml = '';
        $expectedHtml = '';

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasOneLine_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with only 1 line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message">A message with only 1 line.</h1>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasTwoLines_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with only 2 lines.<br>
This is the second line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message mb-0">A message with only 2 lines.</h1>
<p class="message">This is the second line.</p>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasThreeLines_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with 3 lines.<br>
This is the second line.<br>
This is the third line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message mb-0">A message with 3 lines.</h1>
<p class="message">This is the second line.<br>
This is the third line.</p>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasBlankSecondLine_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with 3 lines.<br>
<br>
This is the third line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message">A message with 3 lines.</h1>
<p class="message">This is the third line.</p>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasMultipleLinesWithFilledSecondLine_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with 4 lines.<br>
The second line has content.<br>
This is the third line.<br>
Welcome to the fourth line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message mb-0">A message with 4 lines.</h1>
<p class="message">The second line has content.<br>
This is the third line.<br>
Welcome to the fourth line.</p>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_messageHasMultipleLinesWithBlankSecondLine_expectedString()
    {
$messageHtml = <<<MESSAGE_HTML
A message with 4 lines.<br>
<br>
This is the third line.<br>
Welcome to the fourth line.
MESSAGE_HTML;
$expectedHtml = <<<EXPECTED_HTML
<h1 class="message">A message with 4 lines.</h1>
<p class="message">This is the third line.<br>
Welcome to the fourth line.</p>
EXPECTED_HTML;

        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('The message.')
            ;
        $this->toHtmlServiceMock
            ->expects($this->once())
            ->method('toHtml')
            ->with($questionEntity->getMessage())
            ->willReturn($messageHtml);
            ;

        $this->assertSame(
            $expectedHtml,
            $this->messageHelper->__invoke($questionEntity),
        );
    }
}
