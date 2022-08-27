<?php
namespace MonthlyBasis\QuestionTest\Model\Factory\Question;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class FromSlugTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->slugTableMock = $this->createMock(
            QuestionTable\Question\Slug::class
        );

        $this->fromSlugFactory = new QuestionFactory\Question\FromSlug(
            $this->questionFactoryMock,
            $this->slugTableMock,
        );
    }

    public function test_buildFromSlug()
    {
        $resultMock = $this->createMock(Result::class);
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $questionEntity = new QuestionEntity\Question();

        $array = [
            'question_id'      => '12345',
            'slug'             => 'slug',
            'user_id'          => null,
            'name'             => 'name',
            'subject'          => 'subject',
            'message'          => 'message',
            'created_datetime' => '2018-03-12 22:12:23',
            'views'            => '123',
        ];
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                $array,
            ]
        );
        $this->slugTableMock
             ->expects($this->once())
             ->method('selectWhereSlug')
             ->with('slug')
             ->willReturn($resultMock)
             ;
        $this->questionFactoryMock
             ->expects($this->once())
             ->method('buildFromArray')
             ->with($array)
             ->willReturn($questionEntity)
             ;

        $this->assertSame(
            $questionEntity,
            $this->fromSlugFactory->buildFromSlug('slug'),
        );
    }
}
