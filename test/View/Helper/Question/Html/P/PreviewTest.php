<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html\P;

use MonthlyBasis\ContentModeration\View\Helper as ContentModerationHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class PreviewTest extends TestCase
{
    protected function setUp(): void
    {
        $this->stripTagsReplaceBadWordsAndShortenHelperMock = $this->createMock(
            ContentModerationHelper\StripTagsReplaceBadWordsAndShorten::class
        );

        $this->previewHelper = new QuestionHelper\Question\Html\P\Preview(
            $this->stripTagsReplaceBadWordsAndShortenHelperMock
        );
    }

    public function test___invoke_messageIsNotSet_emptyString()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->stripTagsReplaceBadWordsAndShortenHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;

        $this->assertSame(
            '',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_shortMessageIsSet_pWithoutClass()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('short message less than 256 chars')
            ;

        $this->stripTagsReplaceBadWordsAndShortenHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with('short message less than 256 chars', 256, '')
            ->willReturn('result string from helper')
            ;

        $this->assertSame(
            '<p>result string from helper</p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }

    public function test___invoke_longMessageIsSet_pWithClass()
    {
        $longMessage    = str_repeat('long message', 25);
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage($longMessage)
            ;

        $this->stripTagsReplaceBadWordsAndShortenHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with($longMessage, 256, '')
            ->willReturn('result string from helper')
            ;

        $this->assertSame(
            '<p class="a-c-e">result string from helper</p>',
            $this->previewHelper->__invoke($questionEntity),
        );
    }
}
