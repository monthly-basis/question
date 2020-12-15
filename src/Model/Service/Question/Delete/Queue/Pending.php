<?php
namespace MonthlyBasis\Question\Model\Service\Question\Delete\Queue;

use Generator;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Pending
{
    /**
     * Construct.
     *
     * @param QuestionTable\QuestionEditQueue $questionDeleteQueueTable
     */
    public function __construct(
        QuestionTable\QuestionDeleteQueue $questionDeleteQueueTable
    ) {
        $this->questionDeleteQueueTable = $questionDeleteQueueTable;
    }

    /**
     * Get pending.
     *
     * @return Generator
     * @yield array
     */
    public function getPending(
    ): Generator {
        foreach ($this->questionDeleteQueueTable->selectWhereQueueStatusId(0) as $array) {
            yield $array;
        }
    }
}
