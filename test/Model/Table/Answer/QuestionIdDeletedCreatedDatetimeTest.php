<?php
namespace MonthlyBasis\QuestionTest\Model\Table\Answer;

use DateTime;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class QuestionIdDeletedCreatedDatetimeTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->answerTable = new QuestionTable\Answer(
            $this->getSql()
        );
        $this->questionIdDeletedCreatedDatetimeTable = new QuestionTable\Answer\QuestionIdDeletedCreatedDatetime(
            $this->getAdapter()
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTable('answer');
        $this->setForeignKeyChecks(1);
    }

    public function testSelectCountWhereQuestionIdCreatedDatetimeGreaterThanAndMessageEquals()
    {
        $dateTime = DateTime::createFromFormat(
            'U',
            time() - 3600 // One hour ago
        );

        $count = $this->questionIdDeletedCreatedDatetimeTable
            ->selectCountWhereQuestionIdCreatedDatetimeGreaterThanAndMessageEquals(
            12345,
            $dateTime,
            'message'
        );
        $this->assertSame(
            0,
            $count
        );

        $this->answerTable->insertDeprecated(
            12345,
            null,
            'message',
            '1.2.3.4',
            'name',
            '1.2.3.4'
        );
        $this->answerTable->insertDeprecated(
            12345,
            null,
            'message',
            '1.2.3.4',
            'name2',
            '1.2.3.4'
        );

        $count = $this->questionIdDeletedCreatedDatetimeTable
            ->selectCountWhereQuestionIdCreatedDatetimeGreaterThanAndMessageEquals(
            12345,
            $dateTime,
            'message'
        );
        $this->assertSame(
            2,
            $count
        );

        $this->answerTable->insertDeprecated(
            12345,
            null,
            'message3',
            '1.2.3.4',
            'name3',
            '1.2.3.4'
        );

        $count = $this->questionIdDeletedCreatedDatetimeTable
            ->selectCountWhereQuestionIdCreatedDatetimeGreaterThanAndMessageEquals(
            12345,
            $dateTime,
            'message'
        );
        $this->assertSame(
            2,
            $count
        );
    }
}
