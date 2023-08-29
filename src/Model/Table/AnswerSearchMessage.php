<?php
namespace MonthlyBasis\Question\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class AnswerSearchMessage extends LaminasDb\Table
{
    protected Adapter $adapter;
    protected string $table = 'answer_search_message';

    public function __construct(
        protected Sql $sql,
    ) {
        $this->adapter = $sql->getAdapter();
    }

    public function rotate(): Result
    {
        $sql = '
            SET SESSION `long_query_time` = 120;
            SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;

            DROP TABLE IF EXISTS `answer_search_message_new`;
            CREATE TABLE `answer_search_message_new` LIKE `answer_search_message`;

            INSERT INTO `answer_search_message_new` (`answer_id`, `message`)
                SELECT `answer`.`answer_id`
                     , `answer`.`message`
                  FROM `answer`
                  JOIN `question`
                 USING (`question_id`)
                 WHERE `question`.`moved_datetime` IS NULL
                   AND `question`.`deleted_datetime` IS NULL
                   AND `answer`.`deleted_datetime` IS NULL
                 ;

            SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;

            RENAME TABLE `answer_search_message` TO `answer_search_message_old`;
            RENAME TABLE `answer_search_message_new` TO `answer_search_message`;
            DROP TABLE `answer_search_message_old`;

            SET SESSION `long_query_time` = 5;
        ';
        return $this->adapter->createStatement($sql)->execute();
    }

    public function selectAnswerIdWhereMatchMessageAgainstAndAnswerIdNotEquals(
        string $query,
        int $answerId,
        int $limitOffset,
        int $limitRowCount
    ): Result {
        $sql = '
            SELECT `answer_id`
                 , MATCH (`message`) AGAINST (:query) AS `score`
              FROM `answer_search_message`
             WHERE MATCH (`message`) AGAINST (:query)
               AND `answer_id` != :answerId
             ORDER
                BY `score` DESC
             LIMIT :limitOffset, :limitRowCount
                 ;
        ';
        $parameters = [
            'query'         => $query,
            'answerId'      => $answerId,
            'limitOffset'   => $limitOffset,
            'limitRowCount' => $limitRowCount,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
