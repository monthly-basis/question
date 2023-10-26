<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
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
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
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
        $this->relatedService = new QuestionService\Question\Questions\Related(
            $this->memcachedServiceMock,
            $this->configEntity,
            $this->questionFactoryMock,
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

    public function test_getRelated_5found_5returned()
    {
        $questionEntity             = new QuestionEntity\Question();
        $questionEntity->questionId = 123;
        $questionEntity->message    = 'the message';

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
            ->method('selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals')
            ->with(
                'the message',
                123,
                0,
                5,
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
            limit: 5,
        );
        $this->assertSame(
            5,
            count(iterator_to_array($generator))
        );
    }

    public function test_getRelated_12found_12returned()
    {
        $questionEntity             = new QuestionEntity\Question();
        $questionEntity->questionId = 123;
        $questionEntity->message    = 'the message';

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
            ->method('selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals')
            ->with(
                'the message',
                123,
                0,
                12,
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
            limit: 12,
        );
        $this->assertSame(
            12,
            count(iterator_to_array($generator))
        );
    }

    public function test_getRelated_exceptionThrown_emptyGenerator()
    {
        $questionEntity             = new QuestionEntity\Question();
        $questionEntity->questionId = 123;
        $questionEntity->message    = 'the message';

        $this->questionSearchMessageTableMock
            ->expects($this->once())
            ->method('selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals')
            ->with(
                'the message',
                123,
                0,
                10,
            )
             ->will(
                 $this->throwException(new InvalidQueryException()),
             )
            ;
        $this->questionFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromQuestionId')
            ;
        $generator = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            limit: 10,
        );
        $this->assertEmpty(iterator_to_array($generator));
    }
}

