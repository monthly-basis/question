<?php
namespace MonthlyBasis\QuestionTest\Model\Service;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    protected function setUp(): void
    {
        $this->titleService = new QuestionService\Question\Title(
            new StringService\StripTagsAndShorten(
                new StringService\Shorten()
            )
        );
    }

    public function test_getTitle_headlineIsSet()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setHeadline('The Headline');

        $this->assertSame(
            'The Headline',
            $this->titleService->getTitle($questionEntity)
        );
    }

    public function test_getTitle_headlineIsNotSet()
    {
        $questionEntity = new QuestionEntity\Question();

        $message = 'this is the message';
        $questionEntity->setMessage($message);

        $this->assertSame(
            'this is the message',
            $this->titleService->getTitle($questionEntity)
        );

$message = <<<MESSAGE
1. Lincoln's reelection was assured in 1864 because
A. Lincoln created a new unionist ticket nominating Andrew Johnson, a Democrat, as his running mate.
B. The Union army successfully invaded the Deep South taking key objectives.
C. He named William Seward as his running mate.
D. His issuance of the Emancipation Proclamation earned him much more moral support.
**I think it is B.
MESSAGE;
        $questionEntity->setMessage($message);

        $this->assertSame(
            '1. Lincoln\'s reelection was assured in 1864 because A.',
            $this->titleService->getTitle($questionEntity)
        );

$message = <<<MESSAGE
Troy signed up for a new cell phone plan which charges him a fee of $40 per month, plus $0.05 for each text message (t) that he sends. If Troy wants to spend at most $70
MESSAGE;
        $questionEntity->setMessage($message);

        $this->assertSame(
            'Troy signed up for a new cell phone plan which charges him a',
            $this->titleService->getTitle($questionEntity)
        );

$message = <<<MESSAGE
aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
MESSAGE;
        $questionEntity->setMessage($message);
        $this->assertSame(
            'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            $this->titleService->getTitle($questionEntity)
        );
    }
}
