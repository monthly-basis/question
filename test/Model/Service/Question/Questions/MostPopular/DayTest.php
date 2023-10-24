<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Questions\MostPopular;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class DayTest extends TestCase
{
    protected function setUp(): void
    {
        $this->memcachedServiceMock = $this->createMock(
            MemcachedService\Memcached::class
        );
        $this->fromQuestionIdFactoryMock = $this->createMock(
            QuestionFactory\Question\FromQuestionId::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->dayService = new QuestionService\Question\Questions\MostPopular\Day(
            $this->memcachedServiceMock,
            $this->fromQuestionIdFactoryMock,
            $this->questionTableMock,
        );
    }

    /**
     * @todo Test with hydrated Result
     */
    public function test_getQuestions_emptyArray()
    {
        $this->memcachedServiceMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(null)
            ;
        $this->questionTableMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($this->createMock(Result::class))
            ;
        $this->fromQuestionIdFactoryMock
            ->expects($this->exactly(0))
            ->method('buildFromQuestionId')
            ;

        $questionEntities = $this->dayService->getQuestions();

        $this->assertEmpty(
            $questionEntities
        );
    }
}
