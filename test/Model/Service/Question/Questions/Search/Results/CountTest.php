<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\Search\Results;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class CountTest extends TestCase
{
    protected function setUp(): void
    {
        $configPath  = __DIR__ . '/../../../../../../../config/autoload/local.php';
        $configArray = (require $configPath)['monthly-basis']['question'] ?? [];
        $this->configEntity = new QuestionEntity\Config(
            $configArray
        );
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );
        $this->keepFirstWordsServiceMock = $this->createMock(
            StringService\KeepFirstWords::class
        );

        $this->countService = new QuestionService\Question\Questions\Search\Results\Count(
            $this->configEntity,
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

    public function test_getPdoResult_tableModelThrows2Exceptions_resultAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(3))
             ->method('selectCountWhereMatchMessageAgainst')
             ->withConsecutive(
                ['the amazing search query'],
                ['the amazing search query'],
                ['the amazing search query'],
             )
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     new Result()
                 )
             )
             ;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Search\Results\Count::class);
        $method = $class->getMethod('getPdoResult');
        $method->setAccessible(true);
        $method->invokeArgs($this->countService, ['the amazing search query']);
    }

    public function test_getPdoResult_tableModelThrows5Exceptions_exceptionAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(5))
             ->method('selectCountWhereMatchMessageAgainst')
             ->withConsecutive(
                ['the amazing search query'],
                ['the amazing search query'],
                ['the amazing search query'],
                ['the amazing search query'],
                ['the amazing search query'],
             )
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                 )
             )
             ;

        try {
            $class = new \ReflectionClass(QuestionService\Question\Questions\Search\Results\Count::class);
            $method = $class->getMethod('getPdoResult');
            $method->setAccessible(true);
            $method->invokeArgs($this->countService, ['the amazing search query']);
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Unable to get PDO result.',
                $exception->getMessage(),
            );
        }
    }
}
