<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class YearTest extends TestCase
{
    protected function setUp(): void
    {
        $this->sqlMock = $this->createMock(
            LaminasDb\Sql\Sql::class
        );
        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->yearService = new QuestionService\Question\Questions\Year(
            $this->sqlMock,
            $this->fromQuestionIdFactoryMock,
            $this->questionTableMock
        );
    }

    public function test_getQuestions()
    {
        $generator = $this->yearService->getQuestions(2020);
        $this->assertInstanceOf(
            Generator::class,
            $generator
        );
    }
}
