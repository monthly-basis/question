<?php
namespace MonthlyBasis\QuestionTest\Model\Entity;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        $this->postEntity = $this->getMockForAbstractClass(QuestionEntity\Post::class);
    }

    public function test_instance()
    {
        $this->assertInstanceOf(
            QuestionEntity\Post::class,
            $this->postEntity
        );
    }
}
