<?php
namespace MonthlyBasis\Question\Model\Service\Questions\Related;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Store
{
    public function __construct(
        protected QuestionTable\QuestionSearchMessage $questionSearchMessageTable,
        protected QuestionTable\QuestionsRelated $questionsRelatedTable,
        protected QuestionTable\QuestionsRelatedLog $questionsRelatedLogTable,
    ) {
    }

    public function storeRelatedQuestions(
        QuestionEntity\Question $questionEntity,
        int $limit = 4,
        int $queryWordCount = 16,
    ): void {
        $query = $questionEntity->message;
        $query = strip_tags($query);
        $query = preg_replace('/\s+/s', ' ', $query);
        $words = explode(' ', $query, ($queryWordCount + 1));
        $query = implode(' ', array_slice($words, 0, $queryWordCount));
        $query = strtolower($query);

        $result = $this->questionSearchMessageTable
            ->selectQuestionIdWhereMatchMessageAgainstAndQuestionIdNotEquals(
                query: $query,
                questionId: $questionEntity->questionId,
                outerLimitOffset: 0,
                outerLimitRowCount: $limit,
            );

        $connection = $this->questionsRelatedTable
            ->getAdapter()
            ->getDriver()
            ->getConnection();

        foreach ($result as $array) {
            try {
                $connection->beginTransaction();

                $this->questionsRelatedTable->delete([
                    'question_id' => $questionEntity->questionId,
                ]);
                $this->questionsRelatedTable->insert([
                    'question_id' => $questionEntity->questionId,
                    'related_question_id' => $array['question_id'],
                    'score' => $array['score'],
                ]);

                $connection->commit();
            } catch (\Exception) {
                $connection->rollback();
            }
        }

        $this->questionsRelatedLogTable->updateSetRetrievedAtUtcTimestampWhereQuestionId(
            $questionEntity->questionId
        );
    }
}
