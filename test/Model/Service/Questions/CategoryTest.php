<?php

declare(strict_types=1);

namespace MonthlyBasis\QuestionTest\Model\Service\Questions;

use MonthlyBasis\Category\Model\Entity as CategoryEntity;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactoryMock;
    protected QuestionService\Questions\Category $categoryQuestionsService;

    protected function setUp(): void
    {
        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );

        $this->categoryQuestionsService = new QuestionService\Questions\Category(
            $this->fromQuestionIdFactoryMock,
        );
    }

    /**
     * @todo Setup ORM so that test runs.
     * @todo Test with test data.
     */
    public function test_getQuestions_array()
    {
        $categoryEntity = new CategoryEntity\Category();
        $categoryEntity->categoryId = 1;

        $generator = $this->categoryQuestionsService->getQuestions(
            $categoryEntity
        );

        $this->assertInstanceOf(\Generator::class, $generator);
    }
}
