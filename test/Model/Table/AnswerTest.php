<?php
namespace MonthlyBasis\QuestionTest\Model\Table;

use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class AnswerTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);

        $this->answerTable = new QuestionTable\Answer(
            $this->getSql()
        );
        $this->answerIdTable = new QuestionTable\Answer\AnswerId(
            $this->getAdapter(),
            $this->answerTable
        );
    }

    public function test_insertDeprecatedAndSelectCount()
    {
        $this->assertSame(
            0,
            $this->answerTable->selectCount()
        );
        $this->answerTable->insertDeprecated(
            1, 2, 'first message', null, '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            3, null, 'second message', 'name', '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            5, 6, 'third message', 'another name', '5.6.7.8'
        );
        $this->assertSame(
            3,
            $this->answerTable->selectCount()
        );
    }

    public function testInsertDeleted()
    {
        $answerId = $this->answerTable->insertDeleted(
            12345,
            null,
            'message',
            'name',
            '1.2.3.4',
            '0',
            'foul language'
        );
        $this->assertSame(
            1,
            $answerId
        );

        $array = $this->answerTable->selectWhereAnswerId(1);
        $this->assertSame(
            0,
            $array['deleted_user_id']
        );
        $this->assertSame(
            'foul language',
            $array['deleted_reason']
        );
    }

    public function test_selectMaxCreatedDatetimeWhereQuestionId()
    {
        $result = $this->answerTable->selectMaxCreatedDatetimeWhereQuestionId(
            12345
        );

        $this->assertSame(
            [
                [
                    'MAX(`answer`.`created_datetime`)' => null,
                ],
            ],
            iterator_to_array($result)
        );
    }

    public function test_selectWhereAnswerId()
    {
        $this->answerTable->insertDeprecated(
            1, 2, 'first message', null, '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            3, null, 'second message', 'name', '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            5, 6, 'third message', 'another name', '5.6.7.8'
        );

        $this->assertSame(
            'first message',
            $this->answerTable->selectWhereAnswerId(1)['message']
        );
        $this->assertSame(
            'third message',
            $this->answerTable->selectWhereAnswerId(3)['message']
        );
    }

    public function testSelectWhereQuestionId()
    {
        $generator = $this->answerTable->selectWhereQuestionId(12345);
        $this->assertEmpty(
            iterator_to_array($generator)
        );
    }

    public function test_selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc_result()
    {
        $result = $this->answerTable->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(12345);

        $this->assertEmpty($result);

        $this->answerTable->insertDeprecated(
            12345,
            2,
            'first message',
            null,
            '1.2.3.4',
        );
        $this->answerTable->insertDeleted(
            12345,
            null,
            'second message',
            'name',
            '1.2.3.4',
            '0',
            'deletion reason',
        );
        $this->answerTable->insertDeprecated(
            12345,
            6,
            'third message',
            'another name',
            '5.6.7.8',
        );
        $result = $this->answerTable->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(12345);

        $arrays = iterator_to_array($result);
        $this->assertCount(2, $arrays);
        $this->assertSame(
            'first message',
            $arrays[0]['message'],
        );
        $this->assertSame(
            'third message',
            $arrays[1]['message'],
        );
    }

    public function test_selectWhereUserIdOrderByCreatedDatetimeDesc()
    {
        $generator = $this->answerTable->selectWhereUserIdOrderByCreatedDatetimeDesc(1, 0, 100);
        $this->assertEmpty(
            iterator_to_array($generator)
        );
    }

    public function test_updateWhereAnswerId()
    {
        $this->answerTable->insertDeprecated(
            1, 2, 'first message', null, '1.2.3.4'
        );
        $result = $this->answerTable->updateWhereAnswerId(
            'new name',
            'modified message',
            10,
            'modified reason',
            1
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->answerIdTable->selectWhereAnswerId(1);
        $this->assertSame(
            'new name',
            $result->current()['created_name']
        );
        $this->assertSame(
            'modified message',
            $result->current()['message']
        );
        $this->assertSame(
            'modified reason',
            $result->current()['modified_reason']
        );

        $result = $this->answerTable->updateWhereAnswerId(
            null,
            'modified message',
            10,
            'modified reason',
            1
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
        $result = $this->answerIdTable->selectWhereAnswerId(1);
        $this->assertNull(
            $result->current()['created_name']
        );
    }
}
