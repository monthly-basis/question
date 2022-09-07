<?php
namespace MonthlyBasis\Question\Model\Service\Answer;

use MonthlyBasis\Question\Model\Entity as QuestionEntity;
use MonthlyBasis\Question\Model\Factory as QuestionFactory;
use MonthlyBasis\Question\Model\Service as QuestionService;
use MonthlyBasis\Question\Model\Table as QuestionTable;
use MonthlyBasis\Vote\Model\Service as VoteService;

class Answers
{
    public function __construct(
        QuestionFactory\Answer $answerFactory,
        QuestionTable\Answer $answerTable,
        VoteService\Votes\Multiple $multipleVotesService,
    ) {
        $this->answerFactory        = $answerFactory;
        $this->answerTable          = $answerTable;
        $this->multipleVotesService = $multipleVotesService;
    }

    public function getAnswers(
        QuestionEntity\Question $questionEntity,
        bool $withVotes = false,
    ): array {
        $result = $this->answerTable->selectWhereQuestionIdAndDeletedDatetimeIsNullOrderByCreatedDateTimeAsc(
            $questionEntity->getQuestionId()
        );

        $answerEntities  = [];
        $answerEntityIds = [];
        foreach ($result as $array) {
            $answerEntityIds[] = $array['answer_id'];
            $answerEntities[]  = $this->answerFactory->buildFromArray($array);
        }

        if (!$withVotes) {
            return $answerEntities;
        }

        $votesEntities = $this->multipleVotesService->getMultiple(
            2,
            $answerEntityIds
        );
        foreach ($answerEntities as $answerEntity) {
            $answerEntity->setDownVotes(
                $votesEntities[$answerEntity->getAnswerId()]->getDownVotes()
            );
            $answerEntity->setUpVotes(
                $votesEntities[$answerEntity->getAnswerId()]->getUpVotes()
            );
            $answerEntity->setRating(
                (($answerEntity->getUpVotes() + $answerEntity->getDownVotes()) > 3)
                ? $answerEntity->getUpVotes() - $answerEntity->getDownVotes()
                : 0
            );
        }

        return $answerEntities;
    }
}
