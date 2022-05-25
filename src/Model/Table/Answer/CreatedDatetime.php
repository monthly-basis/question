<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use DateTime;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Db as QuestionDb;

class CreatedDatetime
{
    protected Adapter $adapter;
    protected QuestionDb\Sql $sql;

    public function __construct(QuestionDb\Sql $sql)
    {
        $this->sql     = $sql;
        $this->adapter = $sql->getAdapter();
    }

    public function selectCountWhereCreatedDatetimeGreaterThanAndCreatedIpAndDeletedDatetimeIsNullAndMessageEquals(
        DateTime $createdDatetime,
        string $createdIp,
        string $message
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `answer`
             WHERE `created_datetime` > ?
               AND `created_ip` = ?
               AND `deleted_datetime` IS NULL
               AND `message` = ?
        ';
        $parameters = [
            $createdDatetime->format('Y-m-d H:i:s'),
            $createdIp,
            $message,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
