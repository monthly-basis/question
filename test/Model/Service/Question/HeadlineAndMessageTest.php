<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class HeadlineAndMessageTest extends TestCase
{
    protected function setUp(): void
    {
        $this->headlineAndMessageService = new QuestionService\Question\HeadlineAndMessage();
    }

    public function test_getHeadlineAndMessage_setHeadline_headline()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('This is the headline.')
            ;

        $this->assertSame(
            'This is the headline.',
            $this->headlineAndMessageService->getHeadlineAndMessage($questionEntity),
        );
    }

    public function test_getHeadlineAndMessage_setMessage_message()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setMessage('This is the message.')
            ;

        $this->assertSame(
            'This is the message.',
            $this->headlineAndMessageService->getHeadlineAndMessage($questionEntity),
        );
    }

    public function test_getHeadlineAndMessage_setHeadlineAndMessage_headlineAndMessage()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('This is the headline.')
            ->setMessage('This is the message.')
            ;

        $this->assertSame(
            'This is the headline. This is the message.',
            $this->headlineAndMessageService->getHeadlineAndMessage($questionEntity),
        );
    }
}
