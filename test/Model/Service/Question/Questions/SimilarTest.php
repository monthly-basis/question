<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
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
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionSearchMessageTableMock = $this->createMock(
            QuestionTable\QuestionSearchMessage::class
        );

        $this->similarService = new QuestionService\Question\Questions\Similar(
            $this->memcachedServiceMock,
            $this->questionFactoryMock,
            $this->questionSearchMessageTableMock,
        );

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
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(123);
        $questionEntity->message = 'the message';

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
            ->method('selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals')
            ->with(
                'the message',
                123,
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
                $this->similarQuestions[0],
                $this->similarQuestions[1]
            )
            ;
        $generator = $this->similarService->getSimilar(
            questionEntity: $questionEntity,
        );

        $this->assertSame(
            2,
            count(iterator_to_array($generator))
        );
    }

    public function test_getQuestionIds_tableModelThrowsException_emptyArray()
    {
        $this->questionSearchMessageTableMock
             ->expects($this->once())
             ->method('selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals')
             ->with('the message', 123)
             ->will(
                 $this->throwException(new InvalidQueryException())
             )
             ;

        $questionEntity = new QuestionEntity\Question();
        $questionEntity->questionId = 123;

        $class = new \ReflectionClass(QuestionService\Question\Questions\Similar::class);
        $method = $class->getMethod('getQuestionIds');
        $array = $method->invokeArgs(
            $this->similarService,
            [
                $questionEntity->questionId,
                'the message',
            ]
        );
        $this->assertSame(
            [],
            $array
        );
    }
}
