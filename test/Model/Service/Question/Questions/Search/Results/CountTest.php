<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\Search\Results;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class CountTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );
        $this->keepFirstWordsServiceMock = $this->createMock(
            StringService\KeepFirstWords::class
        );

        $this->countService = new QuestionService\Question\Questions\Search\Results\Count(
            $this->questionSearchMessageTableMock,
            $this->keepFirstWordsServiceMock
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_getCount_searchQuery_int()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'COUNT(*)' => '2718',
                ],
            ]
        );
        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectCountWhereMatchMessageAgainst')
            ->willReturn($resultMock)
            ;

        $this->assertSame(
            2718,
            $this->countService->getCount('the search query'),
        );
    }
}
