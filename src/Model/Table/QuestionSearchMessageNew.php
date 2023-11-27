<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;
use MonthlyBasis\Memcached\Model\Service as MemcachedService;

class QuestionSearchMessageNew extends LaminasDb\Table
{
    protected Adapter $adapter;
    protected string $table = 'question_search_message_new';

    public function __construct(
        protected \Laminas\Db\Sql\Sql $sql,
    ) {
        $this->adapter = $sql->getAdapter();
    }

    public function createLikeQuestionSearchMessage(): Result
    {
        $sql = '
            CREATE TABLE `question_search_message_new` LIKE `question_search_message`;
        ';
        return $this->adapter->query($sql)->execute();
    }

    public function dropTableIfExists(): Result
    {
        $sql = '
            DROP TABLE IF EXISTS `question_search_message_new`;
        ';
        return $this->adapter->query($sql)->execute();
    }
}
