<?php
namespace MonthlyBasis\Question\Model\Service\Question\Questions\MostPopular;

use MonthlyBasis\Memcached\Model\Service as MemcachedService;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Day
{
    public function __construct(
        protected MemcachedService\Memcached $memcachedService,
        protected QuestionFactory\Question $questionFactory,
        protected QuestionTable\Question $questionTable
    ) {
    }

    public function getQuestions(int $limit = 100): array
    {
        $memcachedKey = md5(__METHOD__ . $limit);
        if (null !== ($questionEntities = $this->memcachedService->get($memcachedKey))) {
            return $questionEntities;
        }

        $questionEntities = [];

        $result = $this->questionTable->select(
            columns: $this->questionTable->getSelectColumns(),
            where: [
                'deleted_datetime' => null,
            ],
            order: 'views_not_bot_one_day DESC',
            limit: $limit,
        );

        foreach ($result as $array) {
            $questionEntities[] = $this->questionFactory->buildFromArray($array);
        }

        $this->memcachedService->setForHours(
            $memcachedKey,
            $questionEntities,
            1
        );
        return $questionEntities;
    }
}
