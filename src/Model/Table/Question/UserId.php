<?php
namespace MonthlyBasis\Question\Model\Table\Question;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class UserId
{
    public function __construct(
        protected Adapter $adapter,
    ) {
    }

    public function selectUserIdOrderByMaxCreatedDatetime(): Result
    {
        $sql = '
            SELECT `user_id`
              FROM `question`
             WHERE `user_id` IS NOT NULL
               AND `deleted_datetime` IS NULL
             GROUP
                BY `user_id`
             ORDER
                BY MAX(`created_datetime`) DESC
             LIMIT 10
                 ;
        ';
        return $this->adapter ->query($sql)->execute();
    }
}
