<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\MostPopular;

use Generator;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class HourTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->hourService = new QuestionService\Question\Questions\MostPopular\Hour(
            $this->fromQuestionIdFactoryMock,
            $this->questionTableMock,
        );
    }

    /**
     * @todo Test with hydrated Result
     */
    public function test_getQuestions_generatorWithZeroResults()
    {
        $this->questionTableMock
            ->expects($this->once())
            ->method('selectQuestionIdOrderByViewsNotBotOneHour')
            ->willReturn($this->createMock(Result::class))
            ;
        $this->fromQuestionIdFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromQuestionId')
            ;

        $generator = $this->hourService->getQuestions();

        $this->assertEmpty(
            iterator_to_array($generator)
        );
    }
}
