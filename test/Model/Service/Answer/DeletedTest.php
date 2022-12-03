<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class DeletedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->deletedService = new QuestionService\Answer\Deleted();
    }

    public function test_isDeleted_entityNotDeleted_false()
    {
        $answerEntity = new QuestionEntity\Answer();

        $this->assertFalse(
            $this->deletedService->isDeleted($answerEntity)
        );
    }

    public function test_isDeleted_entityDeleted_true()
    {
        $answerEntity = (new QuestionEntity\Answer())
            ->setDeletedDateTime(new \DateTime());

        $this->assertTrue(
            $this->deletedService->isDeleted($answerEntity)
        );
    }
}
