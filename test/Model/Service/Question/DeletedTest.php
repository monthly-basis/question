<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class DeletedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->deletedService = new QuestionService\Question\Deleted();
    }

    public function test_isDeleted_entityNotDeleted_false()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->assertFalse(
            $this->deletedService->isDeleted($questionEntity)
        );
    }

    public function test_isDeleted_entityDeleted_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setDeletedDateTime(new \DateTime());

        $this->assertTrue(
            $this->deletedService->isDeleted($questionEntity)
        );
    }
}
