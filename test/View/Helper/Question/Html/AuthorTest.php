<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Html;

use MonthlyBasis\ContentModeration\View\Helper as ContentModerationHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Exception as QuestionException;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\View\Helper as UserHelper;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->replaceAndEscapeHelperMock = $this->createMock(
            ContentModerationHelper\ReplaceAndEscape::class
        );
        $this->replaceAndUrlencodeHelperMock = $this->createMock(
            ContentModerationHelper\ReplaceAndUrlencode::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->userHtmlHelperMock = $this->createMock(
            UserHelper\UserHtml::class
        );

        $this->authorHelper = new QuestionHelper\Question\Html\Author(
            $this->replaceAndEscapeHelperMock,
            $this->replaceAndUrlencodeHelperMock,
            $this->userFactoryMock,
            $this->userHtmlHelperMock,
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
        $this->userHtmlHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;
        $this->replaceAndUrlencodeHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with('created name')
            ->willReturn('created+name')
            ;
        $this->replaceAndEscapeHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with('created name')
            ->willReturn('created name')
            ;

        $this->assertSame(
            '<a href="/visitors?name=created+name">created name</a>',
            $this->authorHelper->__invoke($questionEntity)
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
        $this->userHtmlHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with($userEntity)
            ->willReturn('<a href="/users/1/user-slug">username</a>');
            ;
        $this->replaceAndUrlencodeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;
        $this->replaceAndEscapeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;

        $this->assertSame(
            '<a href="/users/1/user-slug">username</a>',
            $this->authorHelper->__invoke($questionEntity)
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
        $this->userHtmlHelperMock
            ->expects($this->once())
            ->method('__invoke')
            ->with($userEntity)
            ->willReturn('<a href="/users/1/user-slug">username</a>');
            ;
        $this->replaceAndUrlencodeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;
        $this->replaceAndEscapeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;

        $this->assertSame(
            '<a href="/users/1/user-slug">username</a>',
            $this->authorHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_neitherCreatedNameNorUserIdSet_throwException()
    {
        $questionEntity = new QuestionEntity\Question();

        $this->userFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromUserId')
            ;
        $this->userHtmlHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;
        $this->replaceAndUrlencodeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;
        $this->replaceAndEscapeHelperMock
            ->expects($this->exactly(0))
            ->method('__invoke')
            ;

        $this->expectException(QuestionException::class);
        $this->authorHelper->__invoke($questionEntity);
    }
}
