<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

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

    public function test_getAnswers()
    {
        $questionEntity = (new QuestionEntity\Question())->setQuestionId(123);
        $answerEntities = $this->answersService->getAnswers($questionEntity);

        $this->assertIsArray($answerEntities);
    }
}
