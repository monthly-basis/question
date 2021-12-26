<?php
namespace MonthlyBasis\QuestionTest\Model;

use MonthlyBasis\Question\Model\Exception as QuestionException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->exception = new QuestionException();
    }

    public function test_try_catch()
    {
        try {
            throw new QuestionException('This is the message.');
            $this->fail();
        } catch (QuestionException $questionException) {
            $this->assertSame(
                'This is the message.',
                $questionException->getMessage(),
            );
        }
    }
}
