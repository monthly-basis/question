<?php
namespace MonthlyBasis\QuestionTest\Model\Factory;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class QuestionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->displayNameOrUsernameServiceMock = $this->createMock(
            UserService\DisplayNameOrUsername::class
        );
        $this->questionFactory = new QuestionFactory\Question(
            $this->questionTableMock,
            $this->userFactoryMock,
            $this->displayNameOrUsernameServiceMock
        );
    }

    public function test_buildFromArray_subjectIsNull_questionEntity()
    {
        $this->userFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromUserId')
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->exactly(0))
            ->method('getDisplayNameOrUsername')
            ;
        $array = [
            'created_name'      => 'name',
            'created_datetime'  => '2018-03-12 22:12:23',
            'created_ip'        => '5.6.7.8',
            'deleted_datetime'  => '2018-09-17 21:42:45',
            'headline'          => 'This is the headline.',
            'message'           => 'message',
            'modified_datetime' => '2022-07-13 20:25:11',
            'modified_user_id'  => '54321',
            'modified_reason'   => 'modified reason',
            'moved_datetime'    => '2022-08-04 00:31:03',
            'moved_language'    => 'es',
            'moved_question_id' => '111',
            'moved_user_id'     => '1',
            'question_id'       => 1,
            'subject'           => null,
            'user_id'           => null,
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName($array['created_name'])
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setCreatedIp($array['created_ip'])
            ->setHeadline($array['headline'])
            ->setModifiedDateTime(new DateTime($array['modified_datetime']))
            ->setModifiedUserId(intval($array['modified_user_id']))
            ->setModifiedReason($array['modified_reason'])
            ->setMovedDateTime(new DateTime($array['moved_datetime']))
            ->setMovedLanguage($array['moved_language'])
            ->setMovedQuestionId($array['moved_question_id'])
            ->setMovedUserId(intval($array['moved_user_id']))
            ->setDeletedDateTime(new DateTime($array['deleted_datetime']))
            ->setMessage($array['message'])
            ->setQuestionId($array['question_id'])
            ;

        $this->assertEquals(
            $questionEntity,
            $this->questionFactory->buildFromArray($array)
        );
    }

    public function test_buildFromArray_userIdIsNull_nameIsSetFromArray()
    {
        $this->userFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromUserId')
            ;
        $this->displayNameOrUsernameServiceMock
            ->expects($this->exactly(0))
            ->method('getDisplayNameOrUsername')
            ;
        $array = [
            'created_name'      => 'name',
            'created_datetime'  => '2018-03-12 22:12:23',
            'created_ip'        => '5.6.7.8',
            'deleted_datetime'  => '2018-09-17 21:42:45',
            'headline'          => 'This is the headline.',
            'message'           => 'message',
            'modified_datetime' => '2022-07-13 20:25:11',
            'modified_user_id'  => '54321',
            'modified_reason'   => 'modified reason',
            'question_id'       => 1,
            'subject'           => 'subject',
            'user_id'           => null,
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setCreatedName($array['created_name'])
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setCreatedIp($array['created_ip'])
            ->setHeadline($array['headline'])
            ->setModifiedDateTime(new DateTime($array['modified_datetime']))
            ->setModifiedUserId(intval($array['modified_user_id']))
            ->setModifiedReason($array['modified_reason'])
            ->setDeletedDateTime(new DateTime($array['deleted_datetime']))
            ->setMessage($array['message'])
            ->setQuestionId($array['question_id'])
            ->setSubject($array['subject'])
            ;

        $this->assertEquals(
            $questionEntity,
            $this->questionFactory->buildFromArray($array)
        );
    }

    public function test_buildFromArray_userIdIsNotNull_nameIsSetFromUserService()
    {
        $userEntity = new UserEntity\User();
        $userEntity
            ->setDisplayName('i am foo')
            ->setUserId(12345)
            ->setUsername('Foo')
            ;
        $this->userFactoryMock
            ->expects($this->once())
            ->method('buildFromUserId')
            ->with(12345)
            ->willReturn($userEntity);
        $this->displayNameOrUsernameServiceMock
            ->expects($this->once())
            ->method('getDisplayNameOrUsername')
            ->with($userEntity)
            ->willReturn('i am foo');
        $array = [
            'question_id'      => 1,
            'user_id'          => 12345,
            'created_name'     => null,
            'subject'          => 'subject',
            'views'            => '123',
            'created_datetime' => '2018-03-12 22:12:23',
        ];
        $questionEntity = new QuestionEntity\Question();
        $questionEntity
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setCreatedName('i am foo')
            ->setCreatedUserId((int) $array['user_id'])
            ->setQuestionId((int) $array['question_id'])
            ->setSubject($array['subject'])
            ->setViews((int) $array['views'])
            ;
        $this->assertEquals(
            $questionEntity,
            $this->questionFactory->buildFromArray($array)
        );
    }

    public function test_buildFromQuestionId()
    {
        $this->questionTableMock->method('selectWhereQuestionId')->willReturn(
            [
                'question_id' => 123,
                'user_id'     => null,
                'name'        => 'name',
                'subject'     => 'subject',
                'message'     => 'message',
                'created_datetime'     => '2018-03-12 22:12:23',
                'views'       => '123',
            ]
        );
        $questionEntity = $this->questionFactory->buildFromQuestionId(1);
        $this->assertSame(
            $questionEntity->getQuestionId(),
            123
        );
    }

    public function test_getNewInstance()
    {
        $reflectionClass = new ReflectionClass(QuestionFactory\Question::class);
        $method = $reflectionClass->getMethod('getNewInstance');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            QuestionEntity\Question::class,
            $method->invoke($this->questionFactory)
        );
    }
}
