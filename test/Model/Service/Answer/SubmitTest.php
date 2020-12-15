<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Answer;

use Exception;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class SubmitTest extends TestCase
{
    protected function setUp(): void
    {
        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );
        $this->submitQuestionService = new QuestionService\Answer\Submit(
            $this->flashServiceMock,
            $this->answerFactoryMock,
            $this->answerTableMock
        );
    }

    public function testSubmit()
    {
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';
        $_POST = [];
        try {
            $this->submitQuestionService->submit();
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Invalid form input.',
                $exception->getMessage()
            );
        }

        $_POST = [
            'question-id' => '123',
            'message'     => 'this is the message',
        ];
        $this->assertInstanceOf(
            QuestionEntity\Answer::class,
            $this->submitQuestionService->submit()
        );
    }
}
