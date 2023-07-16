<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use MonthlyBasis\Category\Model\Factory as CategoryFactory;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fromCategoryIdFactoryMock = $this->createMock(
            CategoryFactory\FromCategoryId::class
        );
        $this->categoryQuestionTableMock   = $this->createMock(
            QuestionTable\CategoryQuestion::class
        );

        $this->categoriesService = new QuestionService\Question\Categories(
            $this->fromCategoryIdFactoryMock,
            $this->categoryQuestionTableMock,
        );
    }

    public function test_getCategories()
    {
        $questionEntity = new QuestionEntity\Question();
        $questionEntity->questionId = 12345;

        $this->categoryQuestionTableMock
            ->expects($this->once())
            ->method('select')
            ->with(
                ['category_id'],
                null,
                ['question_id' => 12345],
                ['order'],
                null,
                null,
            )
            ;

        $this->assertEmpty(
            $this->categoriesService->getCategories($questionEntity)
        );
    }
}
