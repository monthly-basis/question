<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answers;

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
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );

        $this->userAnswers = new QuestionService\Answers\User(
            $this->answerFactoryMock,
            $this->answerTableMock,
        );
    }

    public function test_getAnswers_withoutVotes_array()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(12345);

        $this->answerTableMock
            ->expects($this->once())
            ->method('selectWhereUserIdOrderByCreatedDatetimeDesc')
            ->with(12345, 0, 100)
            ;

        $array = iterator_to_array(
            $this->userAnswers->getAnswers($userEntity, 1)
        );
        $this->assertEmpty($array);
    }
}
