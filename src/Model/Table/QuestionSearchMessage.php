<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;

class QuestionSearchMessage
{
    public function __construct(
        MemcachedService\Memcached $memcachedService,
        Adapter $adapter
    ) {
        $this->memcachedService = $memcachedService;
        $this->adapter   = $adapter;
    }

    public function selectQuestionIdWhereMatchAgainstOrderByViewsDescScoreDesc(
        string $query,
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
                        ORDER
                           BY `score` DESC
                        LIMIT :questionSearchMessageLimitOffset, :questionSearchMessageLimitRowCount
                   )
                AS `question_search_message`
              LEFT
              JOIN `question`
             USING (`question_id`)
             ORDER
                BY `question`.`views_not_bot_one_month` DESC
                 , `question_search_message`.`score` DESC
             LIMIT :outerLimitOffset, :outerLimitRowCount
                 ;
        ';
        $parameters = [
            'query'                              => $query,
            'questionSearchMessageLimitOffset'   => $questionSearchMessageLimitOffset,
            'questionSearchMessageLimitRowCount' => $questionSearchMessageLimitRowCount,
            'outerLimitOffset'                   => $outerLimitOffset,
            'outerLimitRowCount'                 => $outerLimitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectCountWhereMatchAgainst(string $query): int
    {
        $cacheKey = md5(__METHOD__ . $query);
        if (null !== ($count = $this->memcachedService->get($cacheKey))) {
            return $count;
        }

        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `question_search_message`
             WHERE MATCH (`message`) AGAINST (?)
                 ;
        ';
        $row = $this->adapter->query($sql)->execute([$query])->current();

        $count = (int) $row['count'];
        $this->memcachedService->setForDays($cacheKey, $count, 28);
        return (int) $row['count'];
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
