<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Day
{
    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionFactory\Question\FromQuestionId $fromQuestionIdFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): array
    {
        $questionIds = $this->getQuestionIds($limit);

        $questionEntities = [];

        foreach ($questionIds as $questionId) {
            $questionEntity = $this->fromQuestionIdFactory->buildFromQuestionId(
                intval($array['question_id'])
            );

            if (isset($questionEntities->deletedDateTime)) {
                continue;
            }

            $questionEntities[] = $questionEntity;
        }

        return $questionEntities;
    }

    protected function getQuestionIds(int $limit): array
    {
        $memcachedKey = md5(__METHOD__ . serialize(func_get_args()));
        if (null !== ($questionIds = $this->memcachedService->get($memcachedKey))) {
            return $questionIds;
        }

        $result = $this->questionTable->select(
            columns: ['question_id'],
            order: 'views_one_day DESC',
            limit: $limit,
        );

        $questionIds = [];
        foreach ($result as $array) {
            $questionIds[] = intval($array['question_id']);
        }

        $this->memcachedService->setForHours(
            $memcachedKey,
            $questionIds,
            1
        );
        return $questionIds;
    }
}
