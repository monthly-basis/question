<?php
namespace MonthlyBasis\Question\Model\Factory\Question;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class FromSlug
{
    public function __construct(
        QuestionFactory\Question $questionFactory,
        QuestionTable\Question\Slug $slugTable
    ) {
        $this->questionFactory = $questionFactory;
        $this->slugTable       = $slugTable;
    }

    public function buildFromSlug(
        string $slug
    ): QuestionEntity\Question {
        return $this->questionFactory->buildFromArray(
            $this->slugTable->selectWhereSlug($slug)->current()
        );
    }
}
