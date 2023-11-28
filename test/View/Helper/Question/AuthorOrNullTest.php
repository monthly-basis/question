<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class AuthorOrNullTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->displayNameOrUsernameServiceMock = $this->createMock(
            UserService\DisplayNameOrUsername::class
        );

        $this->authorOrNullHelper = new QuestionHelper\Question\AuthorOrNull(
            $this->userFactoryMock,
            $this->displayNameOrUsernameServiceMock,
        );
    }

    public function test___invoke_createdNameSet_createdName()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName('created name')
            ;

        $this->userFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromUserId')
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->exactly(0))
            ->method('getDisplayNameOrUsername')
            ;

        $this->assertSame(
            'created name',
            $this->authorOrNullHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_userIdSet_displayNameOrUsername()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedUserId(1)
            ;
        $userEntity = new UserEntity\User();

        $this->userFactoryMock
            ->expects($this->once())
            ->method('buildFromUserId')
            ->with(1)
            ->willReturn($userEntity)
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->once())
            ->method('getDisplayNameOrUsername')
            ->with($userEntity)
            ->willReturn('display name or username')
            ;

        $this->assertSame(
            'display name or username',
            $this->authorOrNullHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_bothCreatedNameAndUserIdSet_displayNameOrUsername()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName('created name')
            ->setCreatedUserId(1)
            ;
        $userEntity = new UserEntity\User();

        $this->userFactoryMock
            ->expects($this->once())
            ->method('buildFromUserId')
            ->with(1)
            ->willReturn($userEntity)
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->once())
            ->method('getDisplayNameOrUsername')
            ->with($userEntity)
            ->willReturn('display name or username')
            ;

        $this->assertSame(
            'display name or username',
            $this->authorOrNullHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_neitherCreatedNameNorUserIdSet_null()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->userFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromUserId')
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->exactly(0))
            ->method('getDisplayNameOrUsername')
            ;

        $this->assertNull(
            $this->authorOrNullHelper->__invoke($questionEntity)
        );
    }
}
