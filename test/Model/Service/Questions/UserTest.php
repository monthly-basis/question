<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Questions;

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
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->userAnswers = new QuestionService\Questions\User(
            $this->questionFactoryMock,
            $this->questionTableMock,
        );
    }

    public function test_getQuestions_result()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(12345);

        $this->questionTableMock
            ->expects($this->once())
            ->method('selectWhereUserIdOrderByCreatedDatetimeDesc')
            ->with(12345, 0, 100)
            ;

        $array = iterator_to_array(
            $this->userAnswers->getQuestions($userEntity, 1)
        );
        $this->assertEmpty($array);
    }
}
