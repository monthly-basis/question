<?php
namespace MonthlyBasis\Question\Model\Service\Question\Insert;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Visitor
{
    public function __construct(
        protected QuestionFactory\Question $questionFactory,
        protected QuestionService\Question\Slug\FromMessage $slugFromMessageService,
        protected QuestionTable\Question $questionTable,
    ) {
    }

    public function insert(
        bool $withSlug = false
    ): QuestionEntity\Question {
        $array = [
            'message'      => $_POST['message'],
            'created_name' => $_POST['name'],
            'created_ip'   => $_SERVER['REMOTE_ADDR'],
        ];

        if ($withSlug) {
            $array['slug'] = $this->slugFromMessageService->getSlug(
                $_POST['message']
            );
        }

        $result = $this->questionTable->insert($array);

        return $this->questionFactory->buildFromQuestionId(
            $result->getGeneratedValue()
        );
    }
}
