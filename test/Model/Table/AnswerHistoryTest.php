<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use Generator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use TypeError;

class AnswerHistoryTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath;

    protected function setUp(): void
    {
        $this->answerTable = new QuestionTable\Answer(
            $this->getSql()
        );
        $this->answerHistoryTable = new QuestionTable\AnswerHistory(
            $this->getAdapter()
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['answer', 'answer_history']);
        $this->setForeignKeyChecks(1);
    }

    public function test_getSelect()
    {
        $sql = $this->answerHistoryTable->getSelect()
            . 'FROM `answer_history` LIMIT 1;';
        $result = $this->getAdapter()->query($sql)->execute();
        $this->assertInstanceOf(
            Result::class,
            $result
        );
    }

    public function test_selectDistinctQuestionId_multipleRows_multipleResults()
    {
        $this->answerTable->insertDeprecated(
            1,
            null,
            'message',
            'created name',
            '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            99,
            null,
            'message',
            'created name',
            '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            99,
            null,
            'message',
            'created name',
            '1.2.3.4'
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            2
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            2
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            2
        );
        $result = $this->answerHistoryTable->selectDistinctAnswerId();
        $this->assertCount(
            2,
            $result
        );
        $this->assertSame(
            [
                ['answer_id' => 1],
                ['answer_id' => 2],
            ],
            iterator_to_array($result)
        );
    }

    public function test_selectWhereAnswerIdOrderByCreatedAsc()
    {
        $this->answerTable->insertDeprecated(
            2,
            123,
            'message',
            'created name',
            '1.2.3.4'
        );

        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $result = $this->answerHistoryTable
            ->selectWhereAnswerIdOrderByCreatedAsc(
                1
            );
        $this->assertSame(
            1,
            $result->current()['answer_history_id']
        );
        $result->next();
        $this->assertSame(
            2,
            $result->current()['answer_history_id']
        );
    }

    public function test_selectWhereAnswerIdOrderByCreatedDesc()
    {
        $this->answerTable->insertDeprecated(
            2,
            123,
            'message',
            'created name',
            '1.2.3.4'
        );

        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $result = $this->answerHistoryTable
            ->selectWhereAnswerIdOrderByCreatedDesc(
                1
            );
        $this->assertSame(
            2,
            $result->current()['answer_history_id']
        );
        $result->next();
        $this->assertSame(
            1,
            $result->current()['answer_history_id']
        );
    }

    public function test_updateSetCreatedWhereAnswerHistoryId_multipleRows()
    {
        $this->answerTable->insertDeprecated(
            99,
            null,
            'message',
            'created name',
            '1.2.3.4'
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );

        $result = $this->answerHistoryTable
            ->updateSetCreatedWhereAnswerHistoryId(
                '2010-04-15 15:07:35',
                3
            );
        $this->assertSame(
            0,
            $result->getAffectedRows()
        );

        $result = $this->answerHistoryTable
            ->updateSetCreatedWhereAnswerHistoryId(
                '2010-04-15 15:07:35',
                2
            );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->answerHistoryTable
            ->selectWhereAnswerIdOrderByCreatedAsc(
                1
            );
        $this->assertSame(
            '2010-04-15 15:07:35',
            $result->current()['created']
        );
    }

    public function test_updateSetModifiedReasonWhereAnswerHistoryId_multipleRows_1AffectedRow()
    {
        $this->answerTable->insertDeprecated(
            99,
            null,
            'message',
            'created name',
            '1.2.3.4'
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );
        $this->answerHistoryTable->insertSelectFromAnswer(
            1
        );

        $result = $this->answerHistoryTable
            ->updateSetModifiedReasonWhereAnswerHistoryId(
                'a modified reason',
                2
            );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );

        $result = $this->answerHistoryTable
            ->selectWhereAnswerIdOrderByCreatedDesc(
                1
            );
        $this->assertSame(
            'a modified reason',
            $result->current()['modified_reason']
        );
        $result->next();
        $this->assertNull(
            $result->current()['modified_reason']
        );

        $result = $this->answerHistoryTable
            ->updateSetModifiedReasonWhereAnswerHistoryId(
                'a modified reason for answer_history_id 1',
                1
            );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->answerHistoryTable
            ->selectWhereAnswerIdOrderByCreatedDesc(
                1
            );
        $this->assertSame(
            'a modified reason for answer_history_id 1',
            iterator_to_array($result)[1]['modified_reason']
        );
    }
}
