<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class HeadlineAndMessageTest extends TestCase
{
    protected function setUp(): void
    {
        $this->headlineAndMessageServiceMock = $this->createMock(
            QuestionService\Question\HeadlineAndMessage::class
        );

        $this->headlineAndMessageHelper = new QuestionHelper\Question\HeadlineAndMessage(
            $this->headlineAndMessageServiceMock
        );
    }

    public function test___invoke()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->headlineAndMessageServiceMock
            ->expects($this->once())
            ->method('getHeadlineAndMessage')
            ->with($questionEntity)
            ->willReturn('headline and message')
            ;

        $this->assertSame(
            'headline and message',
            $this->headlineAndMessageHelper->__invoke($questionEntity)
        );
    }
}
