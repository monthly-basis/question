<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class HeadlineOrSubjectTest extends TestCase
{
    protected function setUp(): void
    {
        $this->headlineOrSubjectServiceMock = $this->createMock(
            QuestionService\Question\HeadlineOrSubject::class
        );

        $this->headlineAndMessageHelper = new QuestionHelper\Question\HeadlineOrSubject(
            $this->headlineOrSubjectServiceMock
        );
    }

    public function test___invoke()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->headlineOrSubjectServiceMock
            ->expects($this->once())
            ->method('getHeadlineOrSubject')
            ->with($questionEntity)
            ->willReturn('headline or subject')
            ;

        $this->assertSame(
            'headline or subject',
            $this->headlineAndMessageHelper->__invoke($questionEntity)
        );
    }
}
