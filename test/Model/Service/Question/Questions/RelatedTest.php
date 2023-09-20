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

class RelatedTest extends TestCase
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
        $this->headlineAndMessageServiceMock = $this->createMock(
            QuestionService\Question\HeadlineAndMessage::class
        );
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );
        $this->relatedService = new QuestionService\Question\Questions\Related(
            $this->configEntity,
            $this->questionFactoryMock,
            $this->headlineAndMessageServiceMock,
            $this->questionSearchMessageTableMock,
        );

        $this->questionEntity = (new QuestionEntity\Question())
            ->setMessage('this is the message')
            ->setQuestionId(123)
            ;
        $this->relatedQuestions = [
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

    public function test_getRelated_3found_2returned()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123)
            ;
        $this->headlineAndMessageServiceMock
            ->expects($this->once())
            ->method('getHeadlineAndMessage')
            ->with($questionEntity)
            ->willReturn('headline and message')
        ;

        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
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
                'headline and message',
                123,
                0,
                12,
                0,
                12
            )
            ->willReturn($resultMock)
            ;

        $this->questionFactoryMock
            ->expects($this->exactly(2))
            ->method('buildFromQuestionId')
            /*
            ->withConsecutive(
                [456],
                [789]
            )
             */
            ->willReturnOnConsecutiveCalls(
                $this->relatedQuestions[0],
                $this->relatedQuestions[1]
            )
            ;
        $generator = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            questionSearchMessageLimitOffset: 0,
            questionSearchMessageLimitRowCount: 12,
            outerLimitOffset: 0,
            outerLimitRowCount: 12,
        );

        $this->assertSame(
            2,
            count(iterator_to_array($generator))
        );
    }

    public function test_getRelated_5found_5returned()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123)
            ;
        $this->headlineAndMessageServiceMock
            ->expects($this->once())
            ->method('getHeadlineAndMessage')
            ->with($questionEntity)
            ->willReturn('headline and message')
        ;

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
                'headline and message',
                123,
                0,
                12,
                0,
                12
            )
            ->willReturn($resultMock)
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(5))
            ->method('buildFromQuestionId')
            /*
            ->withConsecutive(
                [1],
                [2],
                [3],
                [4],
                [5]
            )
             */
            ->willReturnOnConsecutiveCalls(
                $this->relatedQuestions[0],
                $this->relatedQuestions[1],
                $this->relatedQuestions[2],
                $this->relatedQuestions[3],
                $this->relatedQuestions[4]
            )
            ;
        $generator = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            questionSearchMessageLimitOffset: 0,
            questionSearchMessageLimitRowCount: 12,
            outerLimitOffset: 0,
            outerLimitRowCount: 12,
        );
        $this->assertSame(
            5,
            count(iterator_to_array($generator))
        );
    }

    public function test_getRelated_12found_12returned()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123)
            ;
        $this->headlineAndMessageServiceMock
            ->expects($this->once())
            ->method('getHeadlineAndMessage')
            ->with($questionEntity)
            ->willReturn('headline and message')
        ;

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
            ]
        );
        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
            ->with(
                'headline and message',
                123,
                0,
                24,
                0,
                12
            )
            ->willReturn($resultMock)
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(12))
            ->method('buildFromQuestionId')
            /*
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
             */
            ->willReturnOnConsecutiveCalls(
                $this->relatedQuestions[0],
                $this->relatedQuestions[1],
                $this->relatedQuestions[2],
                $this->relatedQuestions[3],
                $this->relatedQuestions[4],
                $this->relatedQuestions[5],
                $this->relatedQuestions[6],
                $this->relatedQuestions[7],
                $this->relatedQuestions[8],
                $this->relatedQuestions[9],
                $this->relatedQuestions[10],
                $this->relatedQuestions[11],
                $this->relatedQuestions[12]
            )
            ;
        $generator = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            questionSearchMessageLimitOffset: 0,
            questionSearchMessageLimitRowCount: 24,
            outerLimitOffset: 0,
            outerLimitRowCount: 12,
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
             /*
             ->withConsecutive(
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
             )
              */
             ->will(
                 $this->onConsecutiveCalls(
                     $this->throwException(new InvalidQueryException()),
                     $this->throwException(new InvalidQueryException()),
                     new Result()
                 )
             )
             ;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Related::class);
        $method = $class->getMethod('getPdoResult');
        $method->invokeArgs(
            $this->relatedService,
            [
                (new QuestionEntity\Question())->setQuestionId(123),
                'the query is the message field of the question entity',
                0,
                12,
                0,
                12,
            ]
        );
    }

    public function test_getPdoResult_tableModelThrows5Exceptions_exceptionAfterRecursiveCalls()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->exactly(5))
             ->method('selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc')
             /*
             ->withConsecutive(
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
                ['the query is the message field of the question entity', 123, 0, 100, 0, 12],
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

        $class = new \ReflectionClass(QuestionService\Question\Questions\Related::class);
        $method = $class->getMethod('getPdoResult');

        try {
            $method->invokeArgs(
                $this->relatedService,
                [
                    (new QuestionEntity\Question())->setQuestionId(123),
                    'the query is the message field of the question entity',
                    0,
                    12,
                    0,
                    12,
                ]
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
