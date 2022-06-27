<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class RootRelativeUrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->titleServiceMock = $this->createMock(
            QuestionService\Question\Title::class
        );
        $this->urlFriendlyServiceMock   = $this->createMock(
            StringService\UrlFriendly::class
        );
        $this->rootRelativeUrlService = new QuestionService\Question\RootRelativeUrl(
            $this->titleServiceMock,
            $this->urlFriendlyServiceMock
        );
    }

    public function test_getRootRelativeUrl_includeQuestionsDirectory_expectedString()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)');

        $this->urlFriendlyServiceMock
            ->method('getUrlFriendly')
            ->willReturn('My-Question-Title')
        ;

        $this->assertSame(
            '/questions/12345/My-Question-Title',
            $this->rootRelativeUrlService->getRootRelativeUrl($questionEntity)
        );
    }

    public function test_getRootRelativeUrl_doNotIncludeQuestionsDirectory_expectedString()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)');

        $this->urlFriendlyServiceMock
            ->method('getUrlFriendly')
            ->willReturn('My-Question-Title')
        ;

        $this->assertSame(
            '/12345/My-Question-Title',
            $this->rootRelativeUrlService->getRootRelativeUrl(
                $questionEntity,
                false
            )
        );
    }
}
