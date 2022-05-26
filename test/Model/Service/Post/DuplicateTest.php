<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Post;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class DuplicateTest extends TestCase
{
    protected function setUp(): void
    {
        $this->createdDatetimeTableMock = $this->createMock(
            QuestionTable\Answer\CreatedDatetime::class
        );

        $this->duplicateService = new QuestionService\Post\Duplicate(
            $this->createdDatetimeTableMock,
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_isDuplicate_countIs0_false()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'COUNT(*)' => '0',
                ],
            ],
        );

        $this->createdDatetimeTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals')
            ->with(
                $this->isInstanceOf(DateTime::class),
                '1.2.3.4',
                'the message',
            )
            ->willReturn($resultMock)
            ;

        $this->assertFalse(
            $this->duplicateService ->isDuplicate('1.2.3.4', 'the message'),
        );
    }

    public function test_isDuplicate_countIs2_true()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'COUNT(*)' => '2',
                ],
            ],
        );

        $this->createdDatetimeTableMock
            ->expects($this->once())
            ->method('selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals')
            ->with(
                $this->isInstanceOf(DateTime::class),
                '1.2.3.4',
                'the message',
            )
            ->willReturn($resultMock)
            ;

        $this->assertTrue(
            $this->duplicateService ->isDuplicate('1.2.3.4', 'the message'),
        );
    }
}
