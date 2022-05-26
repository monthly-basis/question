<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Post\Posts;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class NumberOfPostsRecentlyDeletedForFoulLanguageTest extends TestCase
{
    protected function setUp(): void
    {
        $this->createdIpAnswerTableMock = $this->createMock(
            QuestionTable\Answer\CreatedIp::class
        );
        $this->createdIpQuestionTableMock = $this->createMock(
            QuestionTable\Question\CreatedIp::class
        );

        $this->numberOfPostsRecentlyDeletedForFoulLanguageService = new QuestionService\Post\Posts\NumberOfPostsRecentlyDeletedForFoulLanguage(
            $this->createdIpAnswerTableMock,
            $this->createdIpQuestionTableMock,
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_getNumberOfPostsRecentlyDeletedForFoulLanguage_0plus0_0()
    {
        $createdIpAnswerResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $createdIpAnswerResultMock,
            [
                [
                    'COUNT(*)' => '0',
                ],
            ],
        );
        $createdIpQuestionResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $createdIpQuestionResultMock,
            [
                [
                    'COUNT(*)' => '0',
                ],
            ],
        );

        $this->createdIpAnswerTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason')
            ->with(
                '1.2.3.4',
                $this->isInstanceOf(DateTime::class),
                0,
                'foul language',
            )
            ->willReturn($createdIpAnswerResultMock)
            ;
        $this->createdIpQuestionTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason')
            ->with(
                '1.2.3.4',
                $this->isInstanceOf(DateTime::class),
                0,
                'foul language',
            )
            ->willReturn($createdIpQuestionResultMock)
            ;
        $this->assertSame(
            $this->numberOfPostsRecentlyDeletedForFoulLanguageService
                ->getNumberOfPostsRecentlyDeletedForFoulLanguage(
                     '1.2.3.4'
                ),
            0
        );

    }

    public function test_getNumberOfPostsRecentlyDeletedForFoulLanguage_11plus17_28()
    {
        $createdIpAnswerResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $createdIpAnswerResultMock,
            [
                [
                    'COUNT(*)' => '11',
                ],
            ],
        );
        $createdIpQuestionResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $createdIpQuestionResultMock,
            [
                [
                    'COUNT(*)' => '17',
                ],
            ],
        );

        $this->createdIpAnswerTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason')
            ->with(
                '1.2.3.4',
                $this->isInstanceOf(DateTime::class),
                0,
                'foul language',
            )
            ->willReturn($createdIpAnswerResultMock)
            ;
        $this->createdIpQuestionTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason')
            ->with(
                '1.2.3.4',
                $this->isInstanceOf(DateTime::class),
                0,
                'foul language',
            )
            ->willReturn($createdIpQuestionResultMock)
            ;
        $this->assertSame(
            $this->numberOfPostsRecentlyDeletedForFoulLanguageService
                ->getNumberOfPostsRecentlyDeletedForFoulLanguage(
                     '1.2.3.4'
                ),
            28
        );

    }
}
