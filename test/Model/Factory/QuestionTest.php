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
            $this->displayNameOrUsernameServiceMock,
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
            'answer_count_cached'      => 726,
            'created_name'             => 'name',
            'created_datetime'         => '2018-03-12 22:12:23',
            'created_ip'               => '5.6.7.8',
            'did_you_know'             => 'interesting blurb',
            'deleted_datetime'         => '2018-09-17 21:42:45',
            'image_prompt'             => 'Image prompt.',
            'image_prompt_revised'     => 'Image prompt revised.',
            'image_rru_128x128_webp'   => '/path/to/128x128.webp',
            'image_rru_512x512_webp'   => '/path/to/512x512.webp',
            'image_rru_1024x1024_jpeg' => '/path/to/1024x1024.jpeg',
            'image_rru_1024x1024_png'  => '/path/to/1024x1024.png',
            'message'                  => 'message',
            'modified_datetime'        => '2022-07-13 20:25:11',
            'modified_user_id'         => '54321',
            'modified_reason'          => 'modified reason',
            'moved_country'            => 'zaf',
            'moved_datetime'           => '2022-08-04 00:31:03',
            'moved_language'           => 'es',
            'moved_question_id'        => '111',
            'moved_user_id'            => '1',
            'question_id'              => 1,
            'slug'                     => 'slug',
            'subject'                  => null,
            'top_answer_id_cached'     => 20231126,
            'user_id'                  => null,
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setAnswerCountCached($array['answer_count_cached'])
            ->setCreatedName($array['created_name'])
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setCreatedIp($array['created_ip'])
            ->setDeletedDateTime(new DateTime($array['deleted_datetime']))
            ->setMessage($array['message'])
            ->setModifiedDateTime(new DateTime($array['modified_datetime']))
            ->setModifiedUserId(intval($array['modified_user_id']))
            ->setModifiedReason($array['modified_reason'])
            ->setMovedCountry($array['moved_country'])
            ->setMovedDateTime(new DateTime($array['moved_datetime']))
            ->setMovedLanguage($array['moved_language'])
            ->setMovedQuestionId($array['moved_question_id'])
            ->setMovedUserId(intval($array['moved_user_id']))
            ->setQuestionId($array['question_id'])
            ->setSlug($array['slug'])
            ;
        $questionEntity->didYouKnow            = 'interesting blurb';
        $questionEntity->imagePrompt           = 'Image prompt.';
        $questionEntity->imagePromptRevised    = 'Image prompt revised.';
        $questionEntity->imageRru128x128WebP   = '/path/to/128x128.webp';
        $questionEntity->imageRru512x512WebP   = '/path/to/512x512.webp';
        $questionEntity->imageRru1024x1024Jpeg = '/path/to/1024x1024.jpeg';
        $questionEntity->imageRru1024x1024Png  = '/path/to/1024x1024.png';
        $questionEntity->topAnswerIdCached     = 20231126;

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
            'answer_count_cached' => 726,
            'created_name'        => 'name',
            'created_datetime'    => '2018-03-12 22:12:23',
            'created_ip'          => '5.6.7.8',
            'deleted_datetime'    => '2018-09-17 21:42:45',
            'message'             => 'message',
            'modified_datetime'   => '2022-07-13 20:25:11',
            'modified_user_id'    => '54321',
            'modified_reason'     => 'modified reason',
            'question_id'         => 1,
            'subject'             => 'subject',
            'user_id'             => null,
            'views_one_year'      => 12345,
        ];
        $questionEntity = (new QuestionEntity\Question())
            ->setAnswerCountCached($array['answer_count_cached'])
            ->setCreatedName($array['created_name'])
            ->setCreatedDateTime(new DateTime($array['created_datetime']))
            ->setCreatedIp($array['created_ip'])
            ->setModifiedDateTime(new DateTime($array['modified_datetime']))
            ->setModifiedUserId(intval($array['modified_user_id']))
            ->setModifiedReason($array['modified_reason'])
            ->setDeletedDateTime(new DateTime($array['deleted_datetime']))
            ->setMessage($array['message'])
            ->setQuestionId($array['question_id'])
            ->setSubject($array['subject'])
            ;
        $questionEntity->viewsOneYear = 12345;

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
            'answer_count_cached' => 726,
            'question_id'         => 1,
            'user_id'             => 12345,
            'created_name'        => null,
            'subject'             => 'subject',
            'views'               => '123',
            'created_datetime'    => '2018-03-12 22:12:23',
        ];
        $questionEntity = new QuestionEntity\Question();
        $questionEntity
            ->setAnswerCountCached(726)
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
                'answer_count_cached' => 726,
                'question_id'         => 123,
                'user_id'             => null,
                'name'                => 'name',
                'subject'             => 'subject',
                'message'             => 'message',
                'created_datetime'    => '2018-03-12 22:12:23',
                'views'               => '123',
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

        $this->assertInstanceOf(
            QuestionEntity\Question::class,
            $method->invoke($this->questionFactory)
        );
    }
}
