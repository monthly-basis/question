<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\QuestionViewNotBotLog;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\Superglobal\Model\Service as SuperglobalService;
use PHPUnit\Framework\TestCase;

class ConditionallyInsertTest extends TestCase
{
    protected function setUp(): void
    {
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->questionViewNotBotLogTableGatewayMock = $this->createMock(
            TableGateway::class
        );
        $this->startsWithServiceMock = $this->createMock(
            StringService\StartsWith::class
        );
        $this->botServiceMock = $this->createMock(
            SuperglobalService\Server\HttpUserAgent\Bot::class
        );

        $this->conditionallyInsertService = new QuestionService\Question\QuestionViewNotBotLog\ConditionallyInsert(
            $this->memcachedServiceMock,
            $this->questionViewNotBotLogTableGatewayMock,
            $this->startsWithServiceMock,
            $this->botServiceMock,
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isBot_false()
    {
        $this->markTestSkipped(
            'Skipping for now while we insert all views.'
        );

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(true)
            ;
        $this->startsWithServiceMock
            ->expects($this->exactly(0))
            ->method('startsWith')
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;
        $result = $this->conditionallyInsertService->conditionallyInsert(
            (new QuestionEntity\Question())
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsNotSet_false()
    {
        $this->markTestSkipped(
            'Skipping for now while we insert all views.'
        );

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->startsWithServiceMock
            ->expects($this->exactly(0))
            ->method('startsWith')
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsNotGoogle_false()
    {
        $this->markTestSkipped(
            'Skipping for now while we insert all views.'
        );

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'HTTP_REFERER'         => 'https://www.bing.com/search?q=hello+world',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        $this->startsWithServiceMock
            ->expects($this->exactly(0))
            ->method('startsWith')
            ;
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsGoogleDoesNotStartWithEnUs_true()
    {
		$this->markTestSkipped(
			'Skipping for now while we insert all views.'
		);

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'ko-KR,ko;q=0.9,en-US;q=0.8,en',
            'HTTP_REFERER'         => 'https://www.google.com/',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        /*
        $this->startsWithServiceMock
            ->expects($this->once())
            ->method('startsWith')
            ->with('ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7', 'en-US')
            ->willReturn(false)
            ;
         */
        /*
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->exactly(0))
            ->method('insert')
            ;
         */
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id'                 => 12345,
                'ip'                          => '1.2.3.4',
                'server_http_accept_language' => 'ko-KR,ko;q=0.9,en-US;q=0.8,en',
                'server_http_referer'         => 'https://www.google.com/',
            ])
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        //$this->assertFalse($result);
        $this->assertTrue($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsGoogleStartsWithEnUsInvalidQueryExceptionThrown_false()
    {
		$this->markTestSkipped(
			'Skipping for now while we insert all views.'
		);

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'HTTP_REFERER'         => 'https://www.google.com/',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        /*
        $this->startsWithServiceMock
            ->expects($this->once())
            ->method('startsWith')
            ->with('en-US,en;q=0.9', 'en-US')
            ->willReturn(true)
            ;
         */
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id'                 => 12345,
                'ip'                          => '1.2.3.4',
                'server_http_accept_language' => 'en-US,en;q=0.9',
                'server_http_referer'         => 'https://www.google.com/',
            ])
            ->will($this->throwException(new InvalidQueryException()))
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertFalse($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_conditionallyInsert_isNotBotRefererIsGoogleStartsWithEnUsNoExceptionThrown_true()
    {
		$this->markTestSkipped(
			'Skipping for now while we insert all views.'
		);

        $_SERVER = [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
            'HTTP_REFERER'         => 'https://www.google.com/',
            'REMOTE_ADDR'          => '1.2.3.4',
        ];

        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ;
        $this->botServiceMock
            ->expects($this->once())
            ->method('isBot')
            ->willReturn(false)
            ;
        /*
        $this->startsWithServiceMock
            ->expects($this->once())
            ->method('startsWith')
            ->with('en-US,en;q=0.9', 'en-US')
            ->willReturn(true)
            ;
         */
        $this->questionViewNotBotLogTableGatewayMock
            ->expects($this->once())
            ->method('insert')
            ->with([
                'question_id'                 => 12345,
                'ip'                          => '1.2.3.4',
                'server_http_accept_language' => 'en-US,en;q=0.9',
                'server_http_referer'         => 'https://www.google.com/',
            ])
            ;

        $result = $this->conditionallyInsertService->conditionallyInsert(
            $questionEntity
        );
        $this->assertTrue($result);
    }
}
