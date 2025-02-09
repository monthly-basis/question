<?php

declare(strict_types=1);

namespace MonthlyBasis\QuestionTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class UserAnsweredQuestionTest extends TestCase
{
    protected LaminasTestHydrator\CountableIterator $countableIteratorHydrator;
    protected QuestionService\UserAnsweredQuestion $userAnsweredQuestionService;
    protected QuestionTable\Answer $answerTableMock;

    protected function setUp(): void
    {
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );

        $this->userAnsweredQuestionService = new QuestionService\UserAnsweredQuestion(
            $this->answerTableMock
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_didUserAnswerQuestion_countIs0_false()
    {
        $questionEntity = new QuestionEntity\Question();
        $questionEntity->questionId = 12345;
        $userEntity = new UserEntity\User();
        $userEntity->userId = 123;

        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'COUNT(*)' => '0',
                ],
            ]
        );

        $this->answerTableMock
            ->expects($this->once())
            ->method('selectCountWhereQuestionIdAndUserId')
            ->with(
                12345,
                123,
            )
            ->willReturn($resultMock)
            ;

        $didUserAnswerQuestion = $this->userAnsweredQuestionService->didUserAnswerQuestion(
            $userEntity,
            $questionEntity,
        );
        $this->assertFalse($didUserAnswerQuestion);
    }

    public function test_didUserAnswerQuestion_countIs1_true()
    {
        $questionEntity = new QuestionEntity\Question();
        $questionEntity->questionId = 12345;
        $userEntity = new UserEntity\User();
        $userEntity->userId = 123;

        $resultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'COUNT(*)' => '1',
                ],
            ]
        );

        $this->answerTableMock
            ->expects($this->once())
            ->method('selectCountWhereQuestionIdAndUserId')
            ->with(
                12345,
                123,
            )
            ->willReturn($resultMock)
            ;

        $didUserAnswerQuestion = $this->userAnsweredQuestionService->didUserAnswerQuestion(
            $userEntity,
            $questionEntity,
        );
        $this->assertTrue($didUserAnswerQuestion);
    }
}
