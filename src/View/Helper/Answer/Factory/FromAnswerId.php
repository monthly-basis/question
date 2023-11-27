<?php
namespace MonthlyBasis\Question\View\Helper\Answer\Factory;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;

class FromAnswerId extends AbstractHelper
{
    public function __construct(
        protected QuestionFactory\Answer\FromAnswerId $fromAnswerIdFactory
    ) {
    }

    public function __invoke(int $answerId): QuestionEntity\Answer
    {
        return $this->fromAnswerIdFactory->buildFromAnswerId(
            $answerId
        );
    }
}
