<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html\P;

use MonthlyBasis\ContentModeration\Model\Service as ContentModerationService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class PreviewTest extends TestCase
{
    protected function setUp(): void
    {
        $this->replaceBadWordsServiceMock = $this->createMock(
            ContentModerationService\Replace\BadWords::class
        );
        $this->replaceLineBreaksServiceMock = $this->createMock(
            ContentModerationService\Replace\LineBreaks::class
        );
        $this->replaceSpacesServiceMock = $this->createMock(
            ContentModerationService\Replace\Spaces::class
        );
        $this->escapeServiceMock = $this->createMock(
            StringService\Escape::class
        );
        $this->shortenServiceMock = $this->createMock(
            StringService\Shorten::class
        );

        $this->previewHelper = new QuestionHelper\Question\Html\P\Preview(
            $this->replaceBadWordsServiceMock,
            $this->replaceLineBreaksServiceMock,
            $this->replaceSpacesServiceMock,
            $this->escapeServiceMock,
            $this->shortenServiceMock,
        );
    }

    public function test___invoke_messageIsNotSet_emptyString()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->assertSame(
            '',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_oneLineShortMessage_expectedString()
    {
        $shortMessage = 'short message less than 256 chars';
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage($shortMessage)
            ;

        $this->replaceBadWordsServiceMock
            ->expects($this->once())
            ->method('replaceBadWords')
            ->with($shortMessage)
            ->willReturn('replace bad words result')
            ;
        $this->replaceLineBreaksServiceMock
            ->expects($this->once())
            ->method('replaceLineBreaks')
            ->with('replace bad words result')
            ->willReturn('replace line breaks result')
            ;
        $this->replaceSpacesServiceMock
            ->expects($this->once())
            ->method('replaceSpaces')
            ->with('replace line breaks result')
            ->willReturn($shortMessage)
            ;
        $this->shortenServiceMock
            ->expects($this->exactly(0))
            ->method('shorten')
            ;
        $this->escapeServiceMock
            ->expects($this->once())
            ->method('escape')
            ->willReturn('escape short message result')
            ;

        $this->assertSame(
            '<p><b>escape short message result</b></p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_oneLineLongMessage_expectedString()
    {
        $longMessage    = str_repeat('long message', 25);
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage($longMessage)
            ;

        $this->replaceBadWordsServiceMock
            ->expects($this->once())
            ->method('replaceBadWords')
            ->with($longMessage)
            ->willReturn('replace bad words result')
            ;
        $this->replaceLineBreaksServiceMock
            ->expects($this->once())
            ->method('replaceLineBreaks')
            ->with('replace bad words result')
            ->willReturn('replace line breaks result')
            ;
        $this->replaceSpacesServiceMock
            ->expects($this->once())
            ->method('replaceSpaces')
            ->with('replace line breaks result')
            ->willReturn($longMessage)
            ;
        $this->shortenServiceMock
            ->expects($this->once())
            ->method('shorten')
            ->willReturn('shorten long message result')
            ;
        $this->escapeServiceMock
            ->expects($this->once())
            ->method('escape')
            ->willReturn('shorten and escape long message result')
            ;

        $this->assertSame(
            '<p class="a-c-e"><b>shorten and escape long message result</b></p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_shortMessageWithMultipleLines_expectedString()
    {
        $shortMessageWithMultipleLines = "Line 1\nLine 2\n\n\nLine 5";
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage($shortMessageWithMultipleLines)
            ;

        $this->replaceBadWordsServiceMock
            ->expects($this->once())
            ->method('replaceBadWords')
            ->with($shortMessageWithMultipleLines)
            ->willReturn('replace bad words result')
            ;
        $this->replaceLineBreaksServiceMock
            ->expects($this->once())
            ->method('replaceLineBreaks')
            ->with('replace bad words result')
            ->willReturn($shortMessageWithMultipleLines)
            ;
        $this->replaceSpacesServiceMock
            ->expects($this->exactly(2))
            ->method('replaceSpaces')
            /*
            ->withConsecutive(
                ['Line 1'],
                ['Line 2   Line 5'],
            )
             */
            ->willReturnOnConsecutiveCalls(
                'replace spaces in first line result',
                'Line 2 Line 5',
            )
            ;
        $this->escapeServiceMock
            ->expects($this->exactly(2))
            ->method('escape')
            /*
            ->withConsecutive(
                ['replace spaces in first line result'],
                ['Line 2 Line 5'],
            )
             */
            ->willReturnOnConsecutiveCalls(
                'first line escaped result',
                'rest of lines escaped result',
            )
            ;

        $this->assertSame(
            '<p><b>first line escaped result</b> rest of lines escaped result</p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_longMessageAcrossMultipleLines_expectedString()
    {
        $longMessage = str_repeat('long message', 25);
        $longMessageAcrossMultipleLines = "Line 1\nLine 2\n$longMessage";
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage($longMessageAcrossMultipleLines)
            ;

        $this->replaceBadWordsServiceMock
            ->expects($this->once())
            ->method('replaceBadWords')
            ->with($longMessageAcrossMultipleLines)
            ->willReturn('replace bad words result')
            ;
        $this->replaceLineBreaksServiceMock
            ->expects($this->once())
            ->method('replaceLineBreaks')
            ->with('replace bad words result')
            ->willReturn($longMessageAcrossMultipleLines)
            ;
        $this->replaceSpacesServiceMock
            ->expects($this->exactly(2))
            ->method('replaceSpaces')
            /*
            ->withConsecutive(
                ['Line 1'],
                ["Line 2 $longMessage"],
            )
             */
            ->willReturnOnConsecutiveCalls(
                'Line 1 with spaces replaced',
                str_repeat('Long message on one line', 20),
            )
            ;
        $this->escapeServiceMock
            ->expects($this->exactly(2))
            ->method('escape')
            /*
            ->withConsecutive(
                ['Line 1 with spaces replaced'],
                ['Rested of lines shortened'],
            )
             */
            ->willReturnOnConsecutiveCalls(
                'Line 1 escaped',
                'Rest of lines escaped',
            )
            ;
        $this->shortenServiceMock
            ->expects($this->once())
            ->method('shorten')
            ->with(str_repeat('Long message on one line', 20))
            ->willReturn('Rested of lines shortened')
            ;

        $this->assertSame(
            '<p class="a-c-e"><b>Line 1 escaped</b> Rest of lines escaped</p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }
}
