<?php
namespace MonthlyBasis\Question\Model\Service\Questions\Subject;

use MonthlyBasis\Question\Model\Table as QuestionTable;

class NumberOfPages
{
    public function __construct(
        QuestionTable\Question\SubjectDeletedDatetimeViewsBrowser $subjectDeletedViewsBrowserTable
    ) {
        $this->subjectDeletedViewsBrowserTable = $subjectDeletedViewsBrowserTable;
    }

    public function getNumberOfPages(
        string $subject
    ): int {
        $count = $this->subjectDeletedViewsBrowserTable->selectCountWhereSubjectEqualsAndDeletedDatetimeIsNull(
            $subject
        );
        return ceil($count / 100);
    }
}
