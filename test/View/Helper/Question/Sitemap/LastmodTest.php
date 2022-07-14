<?php
namespace MonthlyBasis\QuestionTest\View\Helper\Question\Sitemap;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\View\Helper as QuestionHelper;
use PHPUnit\Framework\TestCase;

class LastmodTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );
        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();

        $this->lastmodHelper = new QuestionHelper\Question\Sitemap\Lastmod(
            $this->answerTableMock
        );
    }

    public function test___invoke_maxAnswerCreatedDatetimeNotNull_answerCreatedDatetimeFormat()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setCreatedDateTime(new DateTime('2020-01-01 12:34:56'))
            ;
        $resultMock = $this->createMock(Result::class);
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'MAX(`answer`.`created_datetime`)' => '2022-07-13 21:22:01',
                ],
            ]
        );
        $this->answerTableMock
            ->expects($this->once())
            ->method('selectMaxCreatedDatetimeWhereQuestionId')
            ->with(12345)
            ->willReturn($resultMock)
            ;

        $this->assertSame(
            '2022-07-13T21:22:01Z',
            $this->lastmodHelper->__invoke($questionEntity)
        );
    }

    public function test___invoke_maxAnswerCreatedDatetimeIsNull_questionCreatedDatetimeFormat()
    {
        $questionEntity = (new QuestionEntity\Question())
            ->setQuestionId(12345)
            ->setCreatedDateTime(new DateTime('2020-01-01 12:34:56'))
            ;
        $resultMock = $this->createMock(Result::class);
        $this->countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'MAX(`answer`.`created_datetime`)' => null,
                ],
            ]
        );
        $this->answerTableMock
            ->expects($this->once())
            ->method('selectMaxCreatedDatetimeWhereQuestionId')
            ->with(12345)
            ->willReturn($resultMock)
            ;

        $this->assertSame(
            '2020-01-01T12:34:56Z',
            $this->lastmodHelper->__invoke($questionEntity)
        );
    }
}
