<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Vote\Model\Service as VoteService;
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
        $this->multipleVotesServiceMock = $this->createMock(
            VoteService\Votes\Multiple::class
        );

        $this->answersService = new QuestionService\Answer\Answers(
            $this->answerFactoryMock,
            $this->answerTableMock,
            $this->multipleVotesServiceMock,
        );
    }

    public function test_getAnswers_withoutVotes_array()
    {
        $questionEntity = (new QuestionEntity\Question())->setQuestionId(123);

        $this->answerTableMock
            ->expects($this->once())
            ->method('selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc')
            ->with(123)
            ;
        $this->multipleVotesServiceMock
            ->expects($this->exactly(0))
            ->method('getMultiple')
            ;

        $this->assertIsArray(
            $this->answersService->getAnswers($questionEntity)
        );
    }

    public function test_getAnswers_withVotes_array()
    {
        $questionEntity = (new QuestionEntity\Question())->setQuestionId(123);

        $this->answerTableMock
            ->expects($this->once())
            ->method('selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc')
            ->with(123)
            ;
        $this->multipleVotesServiceMock
            ->expects($this->once())
            ->method('getMultiple')
            ;

        $this->assertIsArray(
            $this->answersService->getAnswers(
                questionEntity: $questionEntity,
                withVotes: true,
            )
        );
    }
}
