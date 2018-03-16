<?php
namespace LeoGalleguillos\Question\Model\Table;

use Exception;
use Generator;
use Zend\Db\Adapter\Adapter;

class Question
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
     * @return int
     */
    public function insert(
        $userId,
        string $subject,
        string $message
    ) : int {
        $sql = '
            INSERT
              INTO `question` (
                   `user_id`, `subject`, `message`, `created`
                   )
            VALUES (:userId, :subject, :message, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            'userId'       => $userId,
            'subject'     => $subject,
            'message' => $message,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
                    ->getGeneratedValue();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `question`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    public function selectOrderByCreatedDesc() : Generator
    {
        $sql = '
            SELECT `question`.`question_id`
                 , `question`.`user_id`
                 , `question`.`subject`
                 , `question`.`message`
                 , `question`.`created`
                 , `question`.`views`
              FROM `question`
             ORDER
                BY `question`.`created` DESC
             LIMIT 100
                 ;
        ';
        foreach ($this->adapter->query($sql)->execute() as $row) {
            yield($row);
        }
    }

    /**
     * Select where question ID.
     *
     * @param int $questionId
     * @return array
     */
    public function selectWhereQuestionId(int $questionId) : array
    {
        $sql = '
            SELECT `question`.`question_id`
                 , `question`.`user_id`
                 , `question`.`subject`
                 , `question`.`message`
                 , `question`.`created`
                 , `question`.`views`
              FROM `question`
             WHERE `question`.`question_id` = :questionId
             ORDER
                BY `question`.`created` ASC
                 ;
        ';
        $parameters = [
            'questionId' => $questionId,
        ];
        return $this->adapter->query($sql)->execute($parameters)->current();
    }

    public function updateViewsWhereQuestionId(int $questionId) : bool
    {
        $sql = '
            UPDATE `question`
               SET `question`.`views` = `question`.`views` + 1
             WHERE `question`.`question_id` = :questionId
                 ;
        ';
        $parameters = [
            'questionId' => $questionId,
        ];
        return (bool) $this->adapter->query($sql, $parameters)->getAffectedRows();
    }
}