<?php
namespace MonthlyBasis\QuestionTest\Model\Entity;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionEntity = new QuestionEntity\Question();
    }

    public function test_settersAndGetters()
    {
        $answerCountCached = 726;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setAnswerCountCached($answerCountCached)
        );
        $this->assertSame(
            $answerCountCached,
            $this->questionEntity->getAnswerCountCached()
        );

        $createdDateTime = new DateTime();
        $this->questionEntity->setCreatedDateTime($createdDateTime);
        $this->assertSame(
            $createdDateTime,
            $this->questionEntity->getCreatedDateTime()
        );

        $createdUserId = 12345;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setCreatedUserId($createdUserId)
        );
        $this->assertSame(
            $createdUserId,
            $this->questionEntity->getCreatedUserId()
        );

        $deletedDateTime = new DateTime();
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setDeletedDateTime($deletedDateTime)
        );
        $this->assertSame(
            $deletedDateTime,
            $this->questionEntity->getDeletedDateTime()
        );

        $deletedUserId = 123;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setDeletedUserId($deletedUserId)
        );
        $this->assertSame(
            $deletedUserId,
            $this->questionEntity->getDeletedUserId()
        );

        $deletedReason = 'this is the reason';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setDeletedReason($deletedReason)
        );
        $this->assertSame(
            $deletedReason,
            $this->questionEntity->getDeletedReason()
        );

        $headline = 'headline';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setHeadline($headline)
        );
        $this->assertSame(
            $headline,
            $this->questionEntity->getHeadline()
        );

        $modifiedDateTime = new DateTime();
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setModifiedDateTime($modifiedDateTime)
        );
        $this->assertSame(
            $modifiedDateTime,
            $this->questionEntity->getModifiedDateTime()
        );

        $modifiedReason = 'modified reason';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setModifiedReason($modifiedReason)
        );
        $this->assertSame(
            $modifiedReason,
            $this->questionEntity->getModifiedReason()
        );

        $modifiedUserId = 54321;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setModifiedUserId($modifiedUserId)
        );
        $this->assertSame(
            $modifiedUserId,
            $this->questionEntity->getModifiedUserId()
        );

        $movedCountry = 'zaf';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setMovedCountry($movedCountry)
        );
        $this->assertSame(
            $movedCountry,
            $this->questionEntity->getMovedCountry()
        );

        $movedDateTime = new DateTime();
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setMovedDateTime($movedDateTime)
        );
        $this->assertSame(
            $movedDateTime,
            $this->questionEntity->getMovedDateTime()
        );

        $movedLanguage = 'es';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setMovedLanguage($movedLanguage)
        );
        $this->assertSame(
            $movedLanguage,
            $this->questionEntity->getMovedLanguage()
        );

        $movedQuestionId = 12345;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setMovedQuestionId($movedQuestionId)
        );
        $this->assertSame(
            $movedQuestionId,
            $this->questionEntity->getMovedQuestionId()
        );

        $movedUserId = 1;
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setMovedUserId($movedUserId)
        );
        $this->assertSame(
            $movedUserId,
            $this->questionEntity->getMovedUserId()
        );

        $slug = 'slug';
        $this->assertSame(
            $this->questionEntity,
            $this->questionEntity->setSlug($slug)
        );
        $this->assertSame(
            $slug,
            $this->questionEntity->getSlug()
        );
    }
}
