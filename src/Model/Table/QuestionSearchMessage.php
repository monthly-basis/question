<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;

class QuestionSearchMessage extends LaminasDb\Table
{
    protected string $table = 'question_search_message';

    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected \Laminas\Db\Sql\Sql $sql,
        protected Adapter $adapter,
    ) {}

    public function rotate(): Result
    {
        $sql = '
            SET SESSION `long_query_time` = 60;
            SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;

            drop table if exists question_search_message_new;
            create table question_search_message_new like question_search_message;
            INSERT INTO `question_search_message_new` (`question_id`, `message`)
                SELECT `question_id`, `message` FROM `question`
                 WHERE /*`views_not_bot_one_month` > 0
                   AND*/ `moved_datetime` IS NULL
                   AND `deleted_datetime` IS NULL
                 ;

            SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;

            rename table question_search_message to question_search_message_old;
            rename table question_search_message_new to question_search_message;
            drop table question_search_message_old;

            SET SESSION `long_query_time` = 5;
        ';
        return $this->adapter->createStatement($sql)->execute();
    }

    public function selectQuestionIdWhereMatchAgainstOrderByScoreDesc(
        string $query,
        int $limitOffset,
        int $limitRowCount
    ): Result {
        $sql = '
            SELECT `question_id`
                   , MATCH (`message`) AGAINST (:query) AS `score`
              FROM `question_search_message`
             WHERE MATCH (`message`) AGAINST (:query)
             ORDER
                BY `score` DESC
             LIMIT :limitOffset, :limitRowCount
                 ;
        ';
        $parameters = [
            'query'         => $query,
            'limitOffset'   => $limitOffset,
            'limitRowCount' => $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
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
              FROM `question_search_message`
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

    public function selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
        string $query,
        int $questionId,
        int $questionSearchMessageLimitOffset,
        int $questionSearchMessageLimitRowCount,
        int $outerLimitOffset,
        int $outerLimitRowCount
    ): Result {
        $sql = '
            SELECT `question_id`
              FROM (
                       SELECT `question_id`,
                              MATCH (`message`) AGAINST (:query) AS `score`
                         FROM `question_search_message`
                        WHERE MATCH (`message`) AGAINST (:query)
                          AND `question_id` != :questionId
                        ORDER
                           BY `score` DESC
                        LIMIT :questionSearchMessageLimitOffset, :questionSearchMessageLimitRowCount
                   )
                AS `question_search_message`
              LEFT
              JOIN `question`
             USING (`question_id`)
             ORDER
                BY `question`.`views_not_bot_one_hour` DESC
                 , `question`.`views_not_bot_one_day` DESC
                 , `question`.`views_not_bot_one_week` DESC
                 , `question`.`views_not_bot_one_month` DESC
                 , `question_search_message`.`score` DESC
             LIMIT :outerLimitOffset, :outerLimitRowCount
                 ;
        ';
        $parameters = [
            'query'                              => $query,
            'questionId'                         => $questionId,
            'questionSearchMessageLimitOffset'   => $questionSearchMessageLimitOffset,
            'questionSearchMessageLimitRowCount' => $questionSearchMessageLimitRowCount,
            'outerLimitOffset'                   => $outerLimitOffset,
            'outerLimitRowCount'                 => $outerLimitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectCountWhereMatchMessageAgainst(string $query): Result
    {
        $sql = '
            SELECT COUNT(*)
              FROM `question_search_message`
             WHERE MATCH (`message`) AGAINST (?)
                 ;
        ';
        $parameters = [
            $query,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
