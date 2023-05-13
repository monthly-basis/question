<?php
namespace MonthlyBasis\Question\Model\Service\Question\Insert;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Visitor
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function insert(): QuestionEntity\Question
    {
        $result = $this->questionTable->insert([
            'message'    => $_POST['message'],
            'name'       => $_POST['name'],
            'created_ip' => $_SERVER['REMOTE_ADDR'],
        ]);

        return $this->questionFactory->buildFromQuestionId(
            $result->getGeneratedValue()
        );
    }
}
