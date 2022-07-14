<?php
namespace MonthlyBasis\Question\View\Helper\Question\Sitemap;

use DateTime;
use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Table as QuestionTable;

class Lastmod extends AbstractHelper
{
    public function __construct(
        QuestionTable\Answer $answerTable
    ) {
        $this->answerTable = $answerTable;
    }

    public function __invoke(QuestionEntity\Question $questionEntity): string
    {
        $maxAnswerCreatedDatetimeString = $this->answerTable
            ->selectMaxCreatedDatetimeWhereQuestionId($questionEntity->getQuestionId())
            ->current()['MAX(`answer`.`created_datetime`)'];

        if (!empty($maxAnswerCreatedDatetimeString)) {
            return (new DateTime($maxAnswerCreatedDatetimeString))
                ->format('Y-m-d\TH:i:s\Z');
        }

        return $questionEntity->getCreatedDateTime()->format('Y-m-d\TH:i:s\Z');
    }
}
