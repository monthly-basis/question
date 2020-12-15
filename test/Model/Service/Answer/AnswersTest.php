<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use Generator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class AnswersTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );
        $this->answersService = new QuestionService\Answer\Answers(
            $this->answerFactoryMock,
            $this->answerTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            QuestionService\Answer\Answers::class,
            $this->answersService
        );
    }

    public function testGetAnswers()
    {
        $questionEntity = new QuestionEntity\Question();
        $generator = $this->answersService->getAnswers($questionEntity);

        $this->assertInstanceOf(
            Generator::class,
            $generator
        );
    }
}
