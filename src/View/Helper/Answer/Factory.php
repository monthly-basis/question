<?php
namespace MonthlyBasis\Question\View\Helper\Answer;

use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use Laminas\View\Helper\AbstractHelper;

class Factory extends AbstractHelper
{
    public function __construct(
        QuestionFactory\Answer $answerFactory
    ) {
        $this->answerFactory = $answerFactory;
    }

    public function __invoke()
    {
        return $this->answerFactory;
    }
}
