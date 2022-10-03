<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Answer;

use ArrayObject;
use Exception;
use Generator;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use Laminas\Db\Adapter\Adapter;

class MessageTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->answerTable = new QuestionTable\Answer(
            $this->getSql()
        );
        $this->answerMessageTable = new QuestionTable\Answer\Message(
            $this->getAdapter()
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);
    }

    public function testSelectWhereMessageRegularExpression()
    {
        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            'oba',
            1,
            1
        );
        $results = iterator_to_array($result);
        $this->assertEmpty($results);

        $this->answerTable->insertDeprecated(
            1,
            23094,
            'foobarbaz',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            1,
            31093,
            '&lt;b&gt;',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );

        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            'oba',
            0,
            10
        );
        $results = iterator_to_array($result);
        $this->assertSame(
            $results[0]['answer_id'],
            1
        );

        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            '&lt;',
            0,
            10
        );
        $results = iterator_to_array($result);
        $this->assertSame(
            $results[0]['answer_id'],
            2
        );

        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            '&gt;',
            0,
            10
        );
        $results = iterator_to_array($result);
        $this->assertSame(
            $results[0]['answer_id'],
            2
        );

        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            'hello',
            0,
            10
        );
        $results = iterator_to_array($result);
        $this->assertEmpty($results);

        $result = $this->answerMessageTable->selectWhereMessageRegularExpression(
            '[A-Za-z0-9]+;',
            0,
            10
        );
        $results = iterator_to_array($result);
        $this->assertSame(
            $results[0]['answer_id'],
            2
        );
    }

    public function testUpdateWhereQuestionId()
    {
        $this->answerTable->insertDeprecated(
            1,
            44422,
            'foobarbaz',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );

        $this->assertFalse(
            $this->answerMessageTable->updateWhereAnswerId(
                'foobarbaz',
                1
            )
        );
        $this->assertTrue(
            $this->answerMessageTable->updateWhereAnswerId(
                'foo, bar, baz',
                1
            )
        );
        $this->assertFalse(
            $this->answerMessageTable->updateWhereAnswerId(
                'foobarbaz',
                2
            )
        );
    }
}
