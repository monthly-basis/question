<?php
namespace MonthlyBasis\QuestionTest\Model\Entity;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerEntity = new QuestionEntity\Answer();
    }

    public function test___get()
    {
        $this->assertNull($this->answerEntity->views);
        $this->answerEntity->setViews(123);
        $this->assertSame(
            123,
            $this->answerEntity->views
        );
    }

    public function test___isset()
    {
        $this->assertFalse(
            isset($this->answerEntity->createdName)
        );

        $this->answerEntity->setCreatedName('Created Name');

        $this->assertTrue(
            isset($this->answerEntity->createdName)
        );
    }

    public function test___set()
    {
        $this->answerEntity->views = 123;
        $this->assertSame(
            123,
            $this->answerEntity->getViews()
        );
    }

    public function testGettersAndSetters()
    {
        $createdDateTime = new DateTime();
        $this->answerEntity->setCreatedDateTime($createdDateTime);
        $this->assertSame(
            $createdDateTime,
            $this->answerEntity->getCreatedDateTime()
        );

        $createdUserId = 123;
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setCreatedUserId($createdUserId)
        );
        $this->assertSame(
            $createdUserId,
            $this->answerEntity->getCreatedUserId()
        );

        $deletedDateTime = new DateTime();
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setDeletedDateTime($deletedDateTime)
        );
        $this->assertSame(
            $deletedDateTime,
            $this->answerEntity->getDeletedDateTime()
        );

        $deletedUserId = 123;
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setDeletedUserId($deletedUserId)
        );
        $this->assertSame(
            $deletedUserId,
            $this->answerEntity->getDeletedUserId()
        );

        $deletedReason = 'this is the reason';
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setDeletedReason($deletedReason)
        );
        $this->assertSame(
            $deletedReason,
            $this->answerEntity->getDeletedReason()
        );

        $downVotes = 12409823;
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setDownVotes($downVotes)
        );
        $this->assertSame(
            $downVotes,
            $this->answerEntity->getDownVotes()
        );

        $rating = 3.14159;
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setRating($rating)
        );
        $this->assertSame(
            $rating,
            $this->answerEntity->getRating()
        );

        $upVotes = 124;
        $this->assertSame(
            $this->answerEntity,
            $this->answerEntity->setUpVotes($upVotes)
        );
        $this->assertSame(
            $upVotes,
            $this->answerEntity->getUpVotes()
        );
    }
}
