<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\Search;

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
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );
        $this->keepFirstWordsServiceMock = $this->createMock(
            StringService\KeepFirstWords::class
        );

        $this->resultsService = new QuestionService\Question\Questions\Search\Results(
            $this->questionFactoryMock,
            $this->questionTableMock,
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
        $this->assertEmpty(
            iterator_to_array($results)
        );
    }

    public function test_getPdoResult_tableModelThrowsException_resultAfterRecursiveCall()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(3))
             ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     new Result()
                 )
             )
             ;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Search\Results::class);
        $method = $class->getMethod('getPdoResult');
        $method->setAccessible(true);
        $method->invokeArgs($this->resultsService, ['query', 7]);
    }
}
