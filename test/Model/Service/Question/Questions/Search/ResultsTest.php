<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\Search;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class ResultsTest extends TestCase
{
    protected function setUp(): void
    {
        $configPath  = __DIR__ . '/../../../../../../config/autoload/local.php';
        $configArray = (require $configPath)['monthly-basis']['question'] ?? [];
        $this->configEntity = new QuestionEntity\Config(
            $configArray
        );
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );
        $this->keepFirstWordsServiceMock = $this->createMock(
            StringService\KeepFirstWords::class
        );

        $this->resultsService = new QuestionService\Question\Questions\Search\Results(
            $this->configEntity,
            $this->questionFactoryMock,
            $this->questionSearchMessageTableMock,
            $this->keepFirstWordsServiceMock
        );
    }

    public function test_getResults()
    {
        $results = $this->resultsService->getResults(
            'the search query',
            7
        );
        $this->assertEmpty($results);
    }

    public function test_getPdoResult_tableModelThrows2Exceptions_resultAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(3))
             ->method('selectQuestionIdWhereMatchAgainstOrderByScoreDesc')
             /*
             ->withConsecutive(
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
             )
             */
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     new Result(),
                 )
             )
             ;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Search\Results::class);
        $method = $class->getMethod('getPdoResult');
        $method->invokeArgs($this->resultsService, ['the amazing search query', 7]);
    }

    public function test_getPdoResult_tableModelThrows5Exceptions_exceptionAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(5))
             ->method('selectQuestionIdWhereMatchAgainstOrderByScoreDesc')
             /*
             ->withConsecutive(
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
                ['the amazing search query', 600, 100],
             )
             */
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
            $class = new \ReflectionClass(QuestionService\Question\Questions\Search\Results::class);
            $method = $class->getMethod('getPdoResult');
            $method->invokeArgs($this->resultsService, ['the amazing search query', 7]);
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Unable to get PDO result.',
                $exception->getMessage(),
            );
        }
    }
}
