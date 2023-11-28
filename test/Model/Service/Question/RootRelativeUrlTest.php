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
        $configArray = [
            'answer' => [

            ],
            'question' => [
                'root-relative-url' => [
                    'path-before-question-id' => '/path/before/question-id',
                ],
            ],
            'sleep-when-result-unavailable' => 0,
        ];
        $this->configEntity = new QuestionEntity\Config(
            $configArray
        );
        $this->slugServiceMock = $this->createMock(
            QuestionService\Question\Slug::class
        );

        $this->rootRelativeUrlService = new QuestionService\Question\RootRelativeUrl(
            $this->configEntity,
            $this->slugServiceMock,
        );
    }

    public function test_getRootRelativeUrl_useLocalConfig_expectedString()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)')
            ;

        $this->slugServiceMock
            ->expects($this->once())
            ->method('getSlug')
            ->with($questionEntity)
            ->willReturn('my-question-title')
            ;

        $this->assertSame(
            '/path/before/question-id/12345/my-question-title',
            $this->rootRelativeUrlService->getRootRelativeUrl($questionEntity)
        );
    }

    public function test_getRootRelativeUrl_configIsNotSet_expectedString()
    {
        $this->configEntity->offsetUnset('question');

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)')
            ;

        $this->slugServiceMock
            ->expects($this->once())
            ->method('getSlug')
            ->with($questionEntity)
            ->willReturn('my-question-title')
            ;

        $this->assertSame(
            '/questions/12345/my-question-title',
            $this->rootRelativeUrlService->getRootRelativeUrl($questionEntity)
        );
    }

    public function test_getRootRelativeUrl_configValueIsEmptyString_expectedString()
    {
        $this->configEntity['question'] = [
            'root-relative-url' => [
                'path-before-question-id' => '',
            ],
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)')
            ;

        $this->slugServiceMock
            ->expects($this->once())
            ->method('getSlug')
            ->with($questionEntity)
            ->willReturn('my-question-title')
            ;

        $this->assertSame(
            '/12345/my-question-title',
            $this->rootRelativeUrlService->getRootRelativeUrl($questionEntity)
        );
    }

    public function test_getRootRelativeUrl_customConfig_expectedString()
    {
        $this->configEntity['question'] = [
            'root-relative-url' => [
                'include-question-id'     => false,
                'path-before-question-id' => '/my/custom/path',
            ],
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setSubject('My Amazing Question\'s Subject (Is Great)')
            ;

        $this->slugServiceMock
            ->expects($this->once())
            ->method('getSlug')
            ->with($questionEntity)
            ->willReturn('my-question-title')
            ;

        $this->assertSame(
            '/my/custom/path/my-question-title',
            $this->rootRelativeUrlService->getRootRelativeUrl($questionEntity)
        );

        $reflectionClass = new \ReflectionClass(
            QuestionService\Question\RootRelativeUrl::class
        );
        $reflectionProperty = $reflectionClass->getProperty('cache');
        $reflectionPropertyValue = $reflectionProperty->getValue(
            $this->rootRelativeUrlService
        );
        $this->assertSame(
            '/my/custom/path/my-question-title',
            $reflectionPropertyValue[12345]
        );
    }
}
