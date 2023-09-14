<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class UserId
{
    public function __construct(
        protected Adapter $adapter,
    ) {
    }

    public function selectCountWhereUserId(
        int $userId
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `answer`
             WHERE `answer`.`user_id` = ?
               AND `answer`.`deleted_datetime` IS NULL
                 ;
        ';
        $parameters = [
            $userId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
