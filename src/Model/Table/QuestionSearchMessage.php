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
            RENAME TABLE `question_search_message` TO `question_search_message_old`;
            RENAME TABLE `question_search_message_new` TO `question_search_message`;
            DROP TABLE `question_search_message_old`;
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

    /**
     * This method currently not in use.
     * Before using it again, make sure it is performant.
     */
    public function selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
        string $query,
        int $innerLimitOffset = 0,
        int $innerLimitRowCount = 100,
        int $outerLimitOffset = 0,
        int $outerLimitRowCount = 10,
        array $questionIdNotIn = [],
    ): Result {
        $questionIdNotIn = array_map('intval', $questionIdNotIn);
        $commaDelimitedQuestionIds = implode(', ', $questionIdNotIn);

        $sql = '
            SELECT `question_id`
              FROM (
                       SELECT `question_id`,
                              MATCH (`message`) AGAINST (:query) AS `score`
                         FROM `question_search_message`
                        WHERE MATCH (`message`) AGAINST (:query)
                        ORDER
                           BY `score` DESC
                        LIMIT :innerLimitOffset, :innerLimitRowCount
                   )
                AS `question_search_message`
              JOIN `question`
             USING (`question_id`)
        ';

        if (!empty($commaDelimitedQuestionIds)) {
            $sql .= "
                WHERE `question_id` NOT IN ($commaDelimitedQuestionIds)
            ";
        }

        $sql .= '
             ORDER
                BY `question`.`views_one_year` DESC
                 , `question_search_message`.`score` DESC
             LIMIT :outerLimitOffset, :outerLimitRowCount
                 ;
        ';

        $parameters = [
            'query'              => $query,
            'innerLimitOffset'   => $innerLimitOffset,
            'innerLimitRowCount' => $innerLimitRowCount,
            'outerLimitOffset'   => $outerLimitOffset,
            'outerLimitRowCount' => $outerLimitRowCount,
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
