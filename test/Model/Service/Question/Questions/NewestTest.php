<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class NewestTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->deletedDatetimeCreatedDatetimeTableMock = $this->createMock(
            QuestionTable\Question\DeletedDatetimeCreatedDatetime::class
        );
        $this->newestService = new QuestionService\Question\Questions\Newest(
            $this->questionFactoryMock,
            $this->deletedDatetimeCreatedDatetimeTableMock
        );
    }

    public function test_getNewestQuestions()
    {
        $this->deletedDatetimeCreatedDatetimeTableMock
            ->expects($this->once())
            ->method('selectWhereDeletedDatetimeIsNullOrderByCreatedDatetimeDesc')
            ->with(0, 100)
            ->willReturn($this->yieldArrays());
        $this->questionFactoryMock
            ->expects($this->once())
            ->method('buildFromArray')
            ->with([]);

        $generator = $this->newestService->getNewestQuestions();
        $this->assertInstanceOf(
            QuestionEntity\Question::class,
            $generator->current()
        );
    }

    protected function yieldArrays(): Generator
    {
        yield [];
    }
}
