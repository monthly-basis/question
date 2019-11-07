<?php
namespace LeoGalleguillos\AnswerTest\Model\Factory;

use DateTime;
use LeoGalleguillos\Question\Model\Entity as QuestionEntity;
use LeoGalleguillos\Question\Model\Factory as QuestionFactory;
use LeoGalleguillos\Question\Model\Table as QuestionTable;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    protected function setUp()
    {
        $this->answerTableMock = $this->createMock(
            QuestionTable\Answer::class
        );
        $this->answerHistoryTableMock = $this->createMock(
            QuestionTable\AnswerHistory::class
        );
        $this->answerFactory = new QuestionFactory\Answer(
            $this->answerTableMock,
            $this->answerHistoryTableMock
        );
    }

    public function testBuildFromArray()
    {
        $array = [
            'answer_id'        => 1,
            'question_id'      => 1,
            'user_id'          => null,
            'message'          => 'message',
            'created_datetime' => '2018-03-12 22:12:23',
            'created_ip'       => '5.6.7.8',
            'ip'               => '1.2.3.4',
            'deleted'          => '2018-09-18 11:23:05',
            'deleted_datetime' => '2018-09-18 11:23:05',
        ];

        $answerEntity = new QuestionEntity\Answer();
        $answerEntity->setAnswerId($array['answer_id'])
                     ->setCreatedDateTime(new DateTime($array['created_datetime']))
                     ->setCreatedIp($array['created_ip'])
                     ->setDeletedDateTime(new DateTime($array['deleted']))
                     ->setDeleted(new DateTime($array['deleted']))
                     ->setMessage($array['message'])
                     ->setIp($array['ip'])
                     ->setQuestionId($array['question_id']);

        $this->assertEquals(
            $answerEntity,
            $this->answerFactory->buildFromArray($array)
        );
    }

    public function testBuildFromAnswerId()
    {
        $array = [
            'answer_id'   => 1,
            'question_id' => 1,
            'user_id'     => 1,
            'message'     => 'message',
            'ip'          => '1.2.3.4',
            'created_datetime'     => '2018-03-12 22:12:23',
        ];
        $this->answerTableMock->method('selectWhereAnswerId')->willReturn(
            $array
        );
        $this->answerHistoryTableMock->method('selectWhereAnswerIdOrderByCreatedAscLimit1')->willReturn(
            null
        );

        $answerEntity = new QuestionEntity\Answer();
        $answerEntity->setAnswerId($array['answer_id'])
                     ->setCreatedDateTime(new DateTime($array['created_datetime']))
                     ->setMessage($array['message'])
                     ->setIp($array['ip'])
                     ->setQuestionId($array['question_id'])
                     ->setUserId($array['user_id']);

        $this->assertEquals(
            $answerEntity,
            $this->answerFactory->buildFromAnswerId(1)
        );
    }
}
