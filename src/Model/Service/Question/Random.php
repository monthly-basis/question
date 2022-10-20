<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Model\Service\Question;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\Sql\Where;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Random
{
    public function __construct(
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
    ) {}

    public function getRandomQuestion(): QuestionEntity\Question
    {
        try {
            $questionId = $this->getQuestionId();
        } catch (InvalidQueryException $invalidQueryException) {
            $questionId = 1;
        }

        return $this->fromQuestionIdFactory->buildFromQuestionId(
            $questionId
        );
    }

    protected function getQuestionId(): int
    {
        $result = $this->questionSearchMessageTable->select(
            columns: [
                'max' => new \Laminas\Db\Sql\Expression('MAX(`question_search_message_id`)')
            ],
        );
        $randomNumber = rand(1, $result->current()['max']);

        $where = (new Where())
            ->greaterThanOrEqualTo('question_id', $randomNumber);
        $result = $this->questionSearchMessageTable->select(
            columns: [
                'question_id',
            ],
            where: $where,
            limit: 1,
        );

        return $result->current()['question_id'];
    }
}
