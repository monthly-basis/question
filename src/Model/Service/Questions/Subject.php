<?php
namespace MonthlyBasis\Question\Model\Service\Questions;

use Generator;
use Laminas\Db as LaminasDb;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;

class Subject
{
    public function __construct(
        LaminasDb\Sql\Sql $sql,
        QuestionFactory\Question $questionFactory
    ) {
        $this->questionFactory = $questionFactory;
        $this->sql             = $sql;
    }

    public function getQuestions(
        string $subject,
        int $page
    ): Generator {
        $select = $this->sql
            ->select('question')
            ->columns([
                'question_id',
                'user_id',
                'subject',
                'headline',
                'message',
                'views',
                'answer_count_cached',
                'created_datetime',
                'created_name',
                'created_ip',
                'modified_user_id',
                'modified_datetime',
                'modified_reason',
                'deleted_datetime',
                'deleted_user_id',
                'deleted_reason',
            ])
            ->where([
                'subject' => $subject,
                'moved_datetime' => null,
                'deleted_datetime' => null,
            ])
            ->order([
                'views_one_year DESC',
            ])
            ->limit(100)
            ->offset(($page - 1) * 100)
            ;
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        foreach ($result as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
