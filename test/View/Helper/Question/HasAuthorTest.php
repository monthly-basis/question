<?php

declare(strict_types=1);

namespace MonthlyBasis\QuestionTest\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class HasAuthorTest extends TestCase
{
    protected QuestionHelper\Question\HasAuthor $hasAuthorHelper;

    protected function setUp(): void
    {
        $this->hasAuthorHelper = new QuestionHelper\Question\HasAuthor();
    }

    public function test___invoke_createdNameSet_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName('created name')
            ;

        $this->assertTrue(
            $this->hasAuthorHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_userIdSet_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedUserId(1)
            ;

        $this->assertTrue(
            $this->hasAuthorHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_bothCreatedNameAndUserIdSet_true()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName('created name')
            ->setCreatedUserId(1)
            ;

        $this->assertTrue(
            $this->hasAuthorHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_neitherCreatedNameNorUserIdSet_false()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->assertFalse(
            $this->hasAuthorHelper->__invoke($questionEntity)
        );
    }
}
