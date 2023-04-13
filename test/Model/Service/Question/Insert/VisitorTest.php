<?php
namespace MonthlyBasis\QuestionTest\Model\Service\Question\Insert;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class VisitorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->questionFactoryMock = $this->createMock(
            QuestionFactory\Question::class
        );
        $this->questionTableMock = $this->createMock(
            QuestionTable\Question::class
        );

        $this->visitorService = new QuestionService\Question\Insert\Visitor(
            $this->questionFactoryMock,
            $this->questionTableMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_insert_questionEntity()
    {
        $_POST = [
            'message' => 'message',
            'name'    => 'name',
        ];
        $_SERVER = [
            'REMOTE_ADDR' => '1.2.3.4',
        ];

        $this->questionTableMock
            ->expects($this->once())
            ->method('insertDeprecated')
            ->with(
                null,
                null,
                'message',
                'name',
                '1.2.3.4',
            )
            ->willReturn(54321)
            ;
        $questionEntity = new QuestionEntity\Question();
        $this->questionFactoryMock
             ->expects($this->once())
             ->method('buildFromQuestionId')
             ->with(54321)
             ->willReturn($questionEntity)
             ;

        $this->assertSame(
            $questionEntity,
            $this->visitorService->insert(),
        );
    }
}
