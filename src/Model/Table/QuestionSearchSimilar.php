<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class QuestionSearchSimilar extends LaminasDb\Table
{
    protected string $table = 'question_search_similar';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql,
        protected Adapter $adapter,
    ) {}

    public function rotate(): Result
    {
        $sql = '
            SET SESSION `long_query_time` = 120;
            SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;

            drop table if exists question_search_similar_new;
            create table question_search_similar_new like question_search_similar;
            INSERT INTO `question_search_similar_new` (`question_id`, `message`)
                SELECT `question_id`, `message` FROM `question`
                 WHERE `answer_count_cached` > 0
                   AND `moved_datetime` IS NULL
                   AND `deleted_datetime` IS NULL
                 ;

            SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;

            rename table question_search_similar to question_search_similar_old;
            rename table question_search_similar_new to question_search_similar;
            drop table question_search_similar_old;

            SET SESSION `long_query_time` = 5;
        ';
        return $this->adapter->createStatement($sql)->execute();
    }

    public function selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
        string $query,
        int $questionId,
        int $limitOffset,
        int $limitRowCount
    ): Result {
        $sql = '
            SELECT `question_id`
                 , MATCH (`message`) AGAINST (:query) AS `score`
              FROM `question_search_similar`
             WHERE MATCH (`message`) AGAINST (:query)
               AND `question_id` != :questionId
             ORDER
                BY `score` DESC
             LIMIT :limitOffset, :limitRowCount
                 ;
        ';
        $parameters = [
            'query'         => $query,
            'questionId'    => $questionId,
            'limitOffset'   => $limitOffset,
            'limitRowCount' => $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
