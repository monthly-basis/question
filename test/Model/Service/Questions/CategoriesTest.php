<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Questions;

use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionCategoriesServiceMock = $this->createMock(
            QuestionService\Question\Categories::class
        );

        $this->questionsCategoriesService = new QuestionService\Questions\Categories(
            $this->questionCategoriesServiceMock,
        );
    }

    public function test_getQuestionsCategories_array()
    {
        $questionEntities = [
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
            new QuestionEntity\Question(),
        ];

        $categoryEntityMath = new CategoryEntity\Category();
        $categoryEntityMath->categoryId = 1;
        $categoryEntityMath->name       = 'Math';

        $categoryEntityScience = new CategoryEntity\Category();
        $categoryEntityScience->categoryId = 2;
        $categoryEntityScience->name       = 'Science';

        $categoryEntityArt = new CategoryEntity\Category();
        $categoryEntityArt->categoryId = 3;
        $categoryEntityArt->name       = 'Art';

        $this->questionCategoriesServiceMock
            ->expects($this->exactly(3))
            ->method('getCategories')
            ->willReturnOnConsecutiveCalls(
                [
                    $categoryEntityMath,
                    $categoryEntityArt,
                ],
                [
                    $categoryEntityMath,
                    $categoryEntityScience,
                ],
                []
            )
            ;

        $this->assertSame(
            [
                $categoryEntityArt,
                $categoryEntityMath,
                $categoryEntityScience,
            ],
            $this->questionsCategoriesService->getQuestionsCategories($questionEntities)
        );
    }
}
