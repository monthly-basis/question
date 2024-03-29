<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    protected function setUp(): void
    {
        $this->titleServiceMock = $this->createMock(
            QuestionService\Question\Title::class
        );
        $this->urlFriendlyServiceMock   = $this->createMock(
            StringService\UrlFriendly::class
        );

        $this->slugService = new QuestionService\Question\Slug(
            $this->titleServiceMock,
            $this->urlFriendlyServiceMock
        );
    }

    public function test_getSlug_urlFriendlyTitle()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->titleServiceMock
            ->expects($this->once())
            ->method('getTitle')
            ->with($questionEntity)
            ->willReturn('The Title')
            ;
        $this->urlFriendlyServiceMock
            ->expects($this->once())
            ->method('getUrlFriendly')
            ->with('The Title')
            ->willReturn('url-friendly-version-of-question-title')
            ;

        $this->assertSame(
            'url-friendly-version-of-question-title',
            $this->slugService->getSlug($questionEntity)
        );
    }
}
