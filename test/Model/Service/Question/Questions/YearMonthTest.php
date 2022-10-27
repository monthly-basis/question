<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class YearMonthTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );
        $this->yearMonthService = new QuestionService\Question\Questions\YearMonth(
            $this->questionFactoryMock,
            $this->questionTableMock
        );
    }

    public function test_getQuestions()
    {
        $generator = $this->yearMonthService->getQuestions(2017, 2);
        $this->assertInstanceOf(
            Generator::class,
            $generator
        );
    }
}
