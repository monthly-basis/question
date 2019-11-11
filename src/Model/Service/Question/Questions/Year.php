<?php
namespace LeoGalleguillos\Question\Model\Service\Question\Questions;

use Generator;
use LeoGalleguillos\Question\Model\Factory as QuestionFactory;
use LeoGalleguillos\Question\Model\Table as QuestionTable;
use TypeError;

class Year
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question\CreatedDatetimeDeletedDatetimeViewsBrowser $createdDatetimeDeletedDatetimeViewsBrowserTable
    ) {
        $this->questionFactory                                 = $questionFactory;
        $this->createdDatetimeDeletedDatetimeViewsBrowserTable = $createdDatetimeDeletedDatetimeViewsBrowserTable;
    }

    public function getQuestions(
        int $year
    ): Generator {
        $betweenMin = "$year-01-01 05:00:00";
        $betweenMax = ($year + 1) . "-01-01 04:59:59";

        $arrays = $this->createdDatetimeDeletedDatetimeViewsBrowserTable
            ->selectWhereCreatedDatetimeBetweenAndDeletedDatetimeIsNullOrderByViewsBrowserDescLimit100(
                $betweenMin,
                $betweenMax
            );

        foreach ($arrays as $array) {
            yield $this->questionFactory->buildFromArray($array);
        }
    }
}
