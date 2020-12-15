<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Delete\Queue;

use Exception;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class AddTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionDeleteQueueTableMock = $this->createMock(
            QuestionTable\QuestionDeleteQueue::class
        );
        $this->addService = new QuestionService\Question\Delete\Queue\Add(
            $this->questionDeleteQueueTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            QuestionService\Question\Delete\Queue\Add::class,
            $this->addService
        );
    }

    public function testDelete()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(1);

        $questionEntity = new QuestionEntity\Question();
        $questionEntity->setQuestionId(1);

        $this->assertFalse(
            $this->addService->add(
                $userEntity,
                $questionEntity,
                'reason'
            )
        );
    }
}
