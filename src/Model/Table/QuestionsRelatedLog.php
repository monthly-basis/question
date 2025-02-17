<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;

class QuestionsRelatedLog extends LaminasDb\Table
{
    protected string $table = 'questions_related_log';

    public function __construct(
        protected Adapter $adapter,
    ) {}

    public function insertOnDuplicateKeyUpdateCase(
        int $questionId,
    ): Result {
        $sql = '
            INSERT
              INTO `questions_related_log` (`question_id`, `misses`)
            VALUES (?, 1)
                ON DUPLICATE KEY
            UPDATE `misses` = CASE
                  WHEN `retrieved_at` IS NOT NULL THEN `misses`
                  ELSE `misses` + 1
               END
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }

    public function insertOnDuplicateKeyUpdateRetrievedAt(
        int $questionId,
    ): Result {
        $sql = '
            INSERT
              INTO `questions_related_log` (`question_id`, `retrieved_at`)
            VALUES (?, UTC_TIMESTAMP())
                ON DUPLICATE KEY
            UPDATE `retrieved_at` = UTC_TIMESTAMP()
                 ;
        ';
        $parameters = [
            $questionId,
        ];
        return $this->getAdapter()->query($sql)->execute($parameters);
    }
}
