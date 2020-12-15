<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use Exception;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerIdTableMock = $this->createMock(
            QuestionTable\Answer\AnswerId::class
        );
        $this->deleteService = new QuestionService\Answer\Delete(
            $this->answerIdTableMock
        );
    }

    public function testDelete()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(1);

        $answerEntity = new QuestionEntity\Answer();
        $answerEntity->setAnswerId(1);

        $this->assertFalse(
            $this->deleteService->delete(
                $userEntity,
                'reason',
                $answerEntity
            )
        );
    }
}
