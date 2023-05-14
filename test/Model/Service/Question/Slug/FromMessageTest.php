<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Slug;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class FromMessageTest extends TestCase
{
    protected function setUp(): void
    {
        $this->stripTagsAndShortenServiceMock = $this->createMock(
            StringService\StripTagsAndShorten::class
        );
        $this->urlFriendlyServiceMock   = $this->createMock(
            StringService\UrlFriendly::class
        );

        $this->slugFromMessageService = new QuestionService\Question\Slug\FromMessage(
            $this->stripTagsAndShortenServiceMock,
            $this->urlFriendlyServiceMock
        );
    }

    public function test_getSlug_string()
    {
        $message = 'this is the message';

        $this->stripTagsAndShortenServiceMock
            ->expects($this->once())
            ->method('stripTagsAndShorten')
            ->with($message)
            ->willReturn('this is the message shortened with no tags')
            ;
        $this->urlFriendlyServiceMock
            ->expects($this->once())
            ->method('getUrlFriendly')
            ->with('this is the message shortened with no tags')
            ->willReturn('this-is-the-message-shortened-with-no-tags')
            ;

        $this->assertSame(
            'this-is-the-message-shortened-with-no-tags',
            $this->slugFromMessageService->getSlug($message)
        );
    }
}
