<?php
namespace MonthlyBasis\Question\Model\Table\Answer;

use DateTime;
use Generator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

class CreatedIp
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return Generator
     */
    public function selectAnswerIdWhereCreatedIp(
        string $createdIp
    ): Generator {
        $sql = '
            SELECT `answer`.`answer_id`
              FROM `answer`
             WHERE `answer`.`created_ip` = ?
             ORDER
                BY `answer`.`created_datetime` DESC
             LIMIT 100
                 ;
        ';
        $parameters = [
            $createdIp,
        ];
        foreach ($this->adapter->query($sql)->execute($parameters) as $array) {
            yield $array['answer_id'];
        }
    }

    public function selectCountWhereCreatedIpDeletedDateTimeGreaterThanDeletedUserIdDeletedReason(
        string $createdIp,
        DateTime $deletedDatetimeMin,
        int $deletedUserId,
        string $deletedReason
    ): Result {
        $sql = '
            SELECT COUNT(*)
              FROM `answer`
             WHERE `created_ip` = ?
               AND `deleted_datetime` > ?
               AND `deleted_user_id` = ?
               AND `deleted_reason` = ?
                 ;
        ';
        $parameters = [
            $createdIp,
            $deletedDatetimeMin->format('Y-m-d H:i:s'),
            $deletedUserId,
            $deletedReason,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
