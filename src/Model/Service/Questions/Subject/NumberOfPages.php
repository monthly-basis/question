<?php
namespace MonthlyBasis\Question\Model\Service\Questions\Subject;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class NumberOfPages
{
    public function __construct(
        protected QuestionTable\Question $questionTable
    ) {}

    public function getNumberOfPages(
        string $subject
    ): int {
        $result = $this->questionTable->select(
            columns: [
                'COUNT(*)' => new \Laminas\Db\Sql\Expression('COUNT(*)')
            ],
            where: [
                'subject'          => $subject,
                'moved_datetime'   => null,
                'deleted_datetime' => null,
            ],
        );
        $count = $result->current()['COUNT(*)'];
        return ceil($count / 100);
    }
}
