<?php
namespace MonthlyBasis\QuestionTest\Model\Factory\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class FromAnswerIdTest extends TestCase
{
    protected function setUp(): void
    {
        $this->answerFactoryMock = $this->createMock(
            QuestionFactory\Answer::class
        );
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );

        $this->fromAnswerIdFactory = new QuestionFactory\Answer\FromAnswerId(
            $this->answerFactoryMock,
            $this->answerTableMock,
        );
    }

    public function test_buildFromAnswerId_answerId_answerEntity()
    {
        $answerEntity = new QuestionEntity\Answer();
        $array = [
            'answer_id' => '123',
            'message'   => 'the message',
        ];
        $this->answerTableMock
            ->expects($this->once())
            ->method('selectWhereAnswerId')
            ->with(123)
            ->willReturn($array)
            ;
        $this->answerFactoryMock
            ->expects($this->once())
            ->method('buildFromArray')
            ->with($array)
            ->willReturn($answerEntity)
            ;

        $this->assertSame(
            $answerEntity,
            $this->fromAnswerIdFactory->buildFromAnswerId(123),
        );
    }
}
