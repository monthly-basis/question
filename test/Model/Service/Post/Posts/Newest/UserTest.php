<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions;

use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->postTableMock = $this->createMock(
            QuestionTable\Post::class
        );

        $this->userService = new QuestionService\Post\Posts\Newest\User(
            $this->answerFactoryMock,
            $this->questionFactoryMock,
            $this->postTableMock
        );
    }

    public function test_getNewestPosts_nonEmptyResult_postEntities()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(54321)
            ;
        $resultMock = $this->createMock(Result::class);
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'entity_type' => 'question',
                    'answer_id'   => null,
                    'question_id' => '123',
                    'user_id'     => '54321',
                ],
                [
                    'entity_type' => 'answer',
                    'answer_id'   => '321',
                    'question_id' => '123',
                    'user_id'     => '54321',
                ],
                [
                    'entity_type' => 'question',
                    'answer_id'   => null,
                    'question_id' => '456',
                    'user_id'     => '54321',
                ],
            ]
        );

        $this->postTableMock
             ->expects($this->once())
             ->method('selectFromAnswerUnionQuestion')
             ->with(54321)
             ->willReturn($resultMock)
             ;

        $answerEntity1 = new QuestionEntity\Answer();
        $this->answerFactoryMock
             ->expects($this->once())
             ->method('buildFromArray')
             ->with([
                 'entity_type' => 'answer',
                 'answer_id'   => '321',
                 'question_id' => '123',
                 'user_id'     => '54321',
             ])
             ->willReturn(
                $answerEntity1
             )
             ;

        $questionEntity1 = new QuestionEntity\Question();
        $questionEntity2 = new QuestionEntity\Question();
        $this->questionFactoryMock
             ->expects($this->exactly(2))
             ->method('buildFromArray')
             ->withConsecutive(
                 [
                     [
                         'entity_type' => 'question',
                         'answer_id'   => null,
                         'question_id' => '123',
                         'user_id'     => '54321',
                     ],
                 ],
                 [
                     [
                         'entity_type' => 'question',
                         'answer_id'   => null,
                         'question_id' => '456',
                         'user_id'     => '54321',
                     ],
                 ]
             )
             ->willReturn(
                 $questionEntity1,
                 $questionEntity2
             )
             ;

        $generator    = $this->userService->getNewestPosts($userEntity);
        $postEntities = iterator_to_array($generator);

        $this->assertSame(
            $questionEntity1,
            $postEntities[0]
        );
        $this->assertSame(
            $answerEntity1,
            $postEntities[1]
        );
        $this->assertSame(
            $questionEntity2,
            $postEntities[2]
        );
    }

    public function test_getNewestPosts_emptyResult_zeroPostEntities()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(54321)
            ;
        $resultMock = $this->createMock(Result::class);
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $countableIteratorHydrator->hydrate(
            $resultMock,
            []
        );

        $this->postTableMock
             ->expects($this->once())
             ->method('selectFromAnswerUnionQuestion')
             ->with(54321)
             ->willReturn($resultMock)
             ;

        $generator = $this->userService->getNewestPosts($userEntity);

        $this->assertEmpty(
            iterator_to_array($generator)
        );
    }
}
