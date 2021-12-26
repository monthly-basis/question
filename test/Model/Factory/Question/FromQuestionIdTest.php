<?php
namespace MonthlyBasis\QuestionTest\Model\Factory\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class FromQuestionIdTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->fromQuestionIdFactory = new QuestionFactory\Question\FromQuestionId(
            $this->questionFactoryMock,
            $this->questionTableMock,
        );
    }

    public function test_buildFromQuestionId()
    {
        $array = [
            'question_id'      => '12345',
            'user_id'          => null,
            'name'             => 'name',
            'subject'          => 'subject',
            'message'          => 'message',
            'created_datetime' => '2018-03-12 22:12:23',
            'views'            => '123',
        ];
        $this->questionTableMock
             ->expects($this->once())
             ->method('selectWhereQuestionId')
             ->with(12345)
             ->willReturn($array)
             ;
        $questionEntity = new QuestionEntity\Question();
        $this->questionFactoryMock
             ->expects($this->once())
             ->method('buildFromArray')
             ->with($array)
             ->willReturn($questionEntity)
             ;

        $this->assertSame(
            $questionEntity,
            $this->fromQuestionIdFactory->buildFromQuestionId(12345)
        );
    }
}
