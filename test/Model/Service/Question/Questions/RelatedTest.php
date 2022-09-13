<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class RelatedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->similarServiceMock = $this->createMock(
            QuestionService\Question\Questions\Similar::class
        );

        $this->relatedService = new QuestionService\Question\Questions\Related(
            $this->similarServiceMock,
        );
    }

    public function test_getRelated()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->similarServiceMock
            ->expects($this->once())
            ->method('getSimilar')
            ->with($questionEntity, 0, 10)
            ->willReturn($this->yieldQuestionEntities())
        ;

        $generator = $this->relatedService->getRelated(
            questionEntity: $questionEntity,
            outerLimitOffset: 0,
            outerLimitRowCount: 10,
        );

        $this->assertSame(
            5,
            count(iterator_to_array($generator))
        );
    }

    protected function yieldQuestionEntities(): Generator
    {
        yield new QuestionEntity\Question();
        yield new QuestionEntity\Question();
        yield new QuestionEntity\Question();
        yield new QuestionEntity\Question();
        yield new QuestionEntity\Question();
    }
}
