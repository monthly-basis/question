<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class AnswerOrQuestionDeletedTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerDeletedServiceMock = $this->createMock(
            QuestionService\Answer\Deleted::class
        );
        $this->questionDeletedServiceMock = $this->createMock(
            QuestionService\Question\Deleted::class
        );
        $this->questionFromAnswerServiceMock = $this->createMock(
            QuestionService\QuestionFromAnswer::class
        );

        $this->answerOrQuestionDeletedService = new QuestionService\AnswerOrQuestionDeleted(
            $this->answerDeletedServiceMock,
            $this->questionDeletedServiceMock,
            $this->questionFromAnswerServiceMock,
        );
    }

    public function test_isAnswerOrQuestionDeleted_neitherAnswerNorQuestionDeleted_false()
    {
        $answerEntity   = new QuestionEntity\Answer();
        $questionEntity = new QuestionEntity\Question();

        $this->answerDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($answerEntity)
            ->willReturn(false);
            ;
        $this->questionFromAnswerServiceMock
            ->expects($this->once())
            ->method('getQuestionFromAnswer')
            ->with($answerEntity)
            ->willReturn($questionEntity);
            ;
        $this->questionDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($questionEntity)
            ->willReturn(false);
            ;

        $this->assertFalse(
            $this->answerOrQuestionDeletedService->isAnswerOrQuestionDeleted($answerEntity)
        );
    }

    public function test_isAnswerOrQuestionDeleted_answerDeletedQuestionNotDeleted_true()
    {
        $answerEntity   = (new QuestionEntity\Answer())
            ->setDeletedDateTime(new DateTime());

        $this->answerDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($answerEntity)
            ->willReturn(true);
            ;
        $this->questionFromAnswerServiceMock
            ->expects($this->exactly(0))
            ->method('getQuestionFromAnswer')
            ;
        $this->questionDeletedServiceMock
            ->expects($this->exactly(0))
            ->method('isDeleted')
            ;

        $this->assertTrue(
            $this->answerOrQuestionDeletedService->isAnswerOrQuestionDeleted($answerEntity)
        );
    }

    public function test_isAnswerOrQuestionDeleted_answerNotDeletedQuestionDeleted_true()
    {
        $answerEntity   = new QuestionEntity\Answer();
        $questionEntity = (new QuestionEntity\Question())
            ->setDeletedDateTime(new DateTime());

        $this->answerDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($answerEntity)
            ->willReturn(false);
            ;
        $this->questionFromAnswerServiceMock
            ->expects($this->once())
            ->method('getQuestionFromAnswer')
            ->with($answerEntity)
            ->willReturn($questionEntity);
            ;
        $this->questionDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($questionEntity)
            ->willReturn(true);
            ;

        $this->assertTrue(
            $this->answerOrQuestionDeletedService->isAnswerOrQuestionDeleted($answerEntity)
        );
    }

    public function test_isAnswerOrQuestionDeleted_answerDeletedQuestionDeleted_true()
    {
        $answerEntity   = (new QuestionEntity\Answer())
            ->setDeletedDateTime(new DateTime());

        $this->answerDeletedServiceMock
            ->expects($this->once())
            ->method('isDeleted')
            ->with($answerEntity)
            ->willReturn(true);
            ;
        $this->questionFromAnswerServiceMock
            ->expects($this->exactly(0))
            ->method('getQuestionFromAnswer')
            ;
        $this->questionDeletedServiceMock
            ->expects($this->exactly(0))
            ->method('isDeleted')
            ;

        $this->assertTrue(
            $this->answerOrQuestionDeletedService->isAnswerOrQuestionDeleted($answerEntity)
        );
    }
}
