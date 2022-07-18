<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use PHPUnit\Framework\TestCase;

class HeadlineOrSubjectTest extends TestCase
{
    protected function setUp(): void
    {
        $this->headlineOrSubjectService = new QuestionService\Question\HeadlineOrSubject();
    }

    public function test_getHeadlineOrSubject_setHeadline_headline()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('This is the headline.')
            ;

        $this->assertSame(
            'This is the headline.',
            $this->headlineOrSubjectService->getHeadlineOrSubject($questionEntity),
        );
    }

    public function test_getHeadlineOrSubject_setSubject_subject()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setSubject('This is the subject.')
            ;

        $this->assertSame(
            'This is the subject.',
            $this->headlineOrSubjectService->getHeadlineOrSubject($questionEntity),
        );
    }

    public function test_getHeadlineOrSubject_setHeadlineAndSubject_headline()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('This is the headline.')
            ->setSubject('This is the subject.')
            ;

        $this->assertSame(
            'This is the headline.',
            $this->headlineOrSubjectService->getHeadlineOrSubject($questionEntity),
        );
    }
}
