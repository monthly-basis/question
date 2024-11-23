<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;

class QuestionsRelated extends LaminasDb\Table
{
    protected string $table = 'questions_related';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql,
        protected Adapter $adapter,
    ) {}
}
