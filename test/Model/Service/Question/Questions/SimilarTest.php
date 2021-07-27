<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use PHPUnit\Framework\TestCase;

class SimilarTest extends TestCase
{
    protected function setUp(): void
    {
        $configPath  = __DIR__ . '/../../../../../config/autoload/local.php';
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
        $this->similarService = new QuestionService\Question\Questions\Similar(
            $this->configEntity,
            $this->questionFactoryMock,
            $this->questionSearchMessageTableMock
        );

        $this->questionEntity = (new QuestionEntity\Question())
            ->setMessage('this is the message')
            ->setQuestionId(123)
            ;
        $this->similarQuestions = [
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question()
        ];
        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_getSimilar_3found_2returned()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'question_id' => '123',
                ],
                [
                    'question_id' => '456',
                ],
                [
                    'question_id' => '789',
                ],
            ]
        );
        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
            ->with(
                'this is the message',
                0,
                100,
                0,
                13
            )
            ->willReturn($resultMock)
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(2))
            ->method('buildFromQuestionId')
            ->withConsecutive(
                [456],
                [789]
            )
            ->willReturnOnConsecutiveCalls(
                $this->similarQuestions[0],
                $this->similarQuestions[1]
            )
            ;
        $generator = $this->similarService->getSimilar(
            $this->questionEntity,
            12
        );
        $this->assertSame(
            2,
            count(iterator_to_array($generator))
        );
    }

    public function test_getSimilar_5found_5returned()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'question_id' => '1',
                ],
                [
                    'question_id' => '2',
                ],
                [
                    'question_id' => '3',
                ],
                [
                    'question_id' => '4',
                ],
                [
                    'question_id' => '5',
                ],
            ]
        );
        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
            ->with(
                'this is the message',
                0,
                100,
                0,
                13
            )
            ->willReturn($resultMock)
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(5))
            ->method('buildFromQuestionId')
            ->withConsecutive(
                [1],
                [2],
                [3],
                [4],
                [5]
            )
            ->willReturnOnConsecutiveCalls(
                $this->similarQuestions[0],
                $this->similarQuestions[1],
                $this->similarQuestions[2],
                $this->similarQuestions[3],
                $this->similarQuestions[4]
            )
            ;
        $generator = $this->similarService->getSimilar(
            $this->questionEntity,
            12
        );
        $this->assertSame(
            5,
            count(iterator_to_array($generator))
        );
    }

    public function test_getSimilar_13found_12returned()
    {
        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'question_id' => '1',
                ],
                [
                    'question_id' => '2',
                ],
                [
                    'question_id' => '3',
                ],
                [
                    'question_id' => '4',
                ],
                [
                    'question_id' => '5',
                ],
                [
                    'question_id' => '6',
                ],
                [
                    'question_id' => '7',
                ],
                [
                    'question_id' => '8',
                ],
                [
                    'question_id' => '9',
                ],
                [
                    'question_id' => '10',
                ],
                [
                    'question_id' => '11',
                ],
                [
                    'question_id' => '12',
                ],
                [
                    'question_id' => '13',
                ],
            ]
        );
        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
            ->with(
                'this is the message',
                0,
                100,
                0,
                13
            )
            ->willReturn($resultMock)
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(12))
            ->method('buildFromQuestionId')
            ->withConsecutive(
                [1],
                [2],
                [3],
                [4],
                [5],
                [6],
                [7],
                [8],
                [9],
                [10],
                [11],
                [12]
            )
            ->willReturnOnConsecutiveCalls(
                $this->similarQuestions[0],
                $this->similarQuestions[1],
                $this->similarQuestions[2],
                $this->similarQuestions[3],
                $this->similarQuestions[4],
                $this->similarQuestions[5],
                $this->similarQuestions[6],
                $this->similarQuestions[7],
                $this->similarQuestions[8],
                $this->similarQuestions[9],
                $this->similarQuestions[10],
                $this->similarQuestions[11],
                $this->similarQuestions[12]
            )
            ;
        $generator = $this->similarService->getSimilar(
            $this->questionEntity,
            12
        );
        $this->assertSame(
            12,
            count(iterator_to_array($generator))
        );
    }

    public function test_getPdoResult_tableModelThrows2Exceptions_resultAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(3))
             ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
             ->withConsecutive(
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
             )
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     new Result()
                 )
             )
             ;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Similar::class);
        $method = $class->getMethod('getPdoResult');
        $method->setAccessible(true);
        $method->invokeArgs(
            $this->similarService,
            ['the query is the message field of the question entity', 12]
        );
    }

    public function test_getPdoResult_tableModelThrows5Exceptions_exceptionAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(5))
             ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
             ->withConsecutive(
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
                ['the query is the message field of the question entity', 0, 100, 0, 13],
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

        $class = new \ReflectionClass(QuestionService\Question\Questions\Similar::class);
        $method = $class->getMethod('getPdoResult');
        $method->setAccessible(true);

        try {
            $method->invokeArgs(
                $this->similarService,
                ['the query is the message field of the question entity', 12]
            );
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Unable to get PDO result.',
                $exception->getMessage(),
            );
        }
    }
}
