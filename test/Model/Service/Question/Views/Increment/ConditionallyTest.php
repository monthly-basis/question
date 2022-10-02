<?php

declare(strict_types=1);

namespace MonthlyBasis\QuestionTest\Model\Service\Question\Views\Increment;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class ConditionallyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->incrementViewsServiceMock = $this->createMock(
            QuestionService\Question\IncrementViews::class
        );

        $this->conditionallyService = new QuestionService\Question\Views\Increment\Conditionally(
            $this->memcachedServiceMock,
            $this->incrementViewsServiceMock,
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyIncrementViews_keyIsNotSet_viewsIncremented()
    {
        $_SERVER = [
            'REMOTE_ADDR' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;

        $this->memcachedServiceMock
            ->expects($this->once())
            ->method('get')
            ->with('12345-2001:0db8:85a3:0000:0000:8a2e:0370:7334')
            ->willReturn(null)
            ;
        $this->memcachedServiceMock
            ->expects($this->once())
            ->method('setForMinutes')
            ->with('12345-2001:0db8:85a3:0000:0000:8a2e:0370:7334', true, 1)
            ;
        $this->incrementViewsServiceMock
            ->expects($this->once())
            ->method('incrementViews')
            ->with($questionEntity)
            ;

        $this->conditionallyService->conditionallyIncrementViews(
            $questionEntity
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyIncrementViews_keyIsSet_viewsNotIncremented()
    {
        $_SERVER = [
            'REMOTE_ADDR' => '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;

        $this->memcachedServiceMock
            ->expects($this->once())
            ->method('get')
            ->with('12345-2001:0db8:85a3:0000:0000:8a2e:0370:7334')
            ->willReturn(true)
            ;
        $this->memcachedServiceMock
            ->expects($this->exactly(0))
            ->method('setForMinutes')
            ;
        $this->incrementViewsServiceMock
            ->expects($this->exactly(0))
            ->method('incrementViews')
            ;

        $this->conditionallyService->conditionallyIncrementViews(
            $questionEntity
        );
    }
}
