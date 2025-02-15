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
        $this->questionCategoriesTableMock   = $this->createMock(
            QuestionTable\QuestionCategories::class
        );

        $this->categoriesService = new QuestionService\Question\Categories(
            $this->fromCategoryIdFactoryMock,
            $this->questionCategoriesTableMock,
        );
    }

    public function test_getCategories()
    {
        $questionEntity = new QuestionEntity\Question();
        $questionEntity->questionId = 12345;

        $this->questionCategoriesTableMock
            ->expects($this->once())
            ->method('selectWhereQuestionId')
            ->with(
                12345
            )
            ;

        $this->assertEmpty(
            $this->categoriesService->getCategories($questionEntity)
        );

		$reflectionClass = new \ReflectionClass($this->categoriesService);
        $reflectionProperty = $reflectionClass->getProperty('cache');
        $this->assertSame(
            [],
            $reflectionProperty->getValue($this->categoriesService)[12345]
        );

        /*
         * Call service again to ensure return value is returned from cache.
         * Otherwise, mock method above would be called more than once.
         */
        $this->categoriesService->getCategories($questionEntity);
    }
}
