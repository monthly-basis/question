<?php
namespace MonthlyBasis\Question\Model\Entity;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Question extends QuestionEntity\Post
{
    protected int $answerCountCached;
    protected $answers;
    protected $createdDateTime;
    protected $createdIp;
    protected $createdName;
    protected $createdUserId;
    protected $deletedDateTime;
    protected $deletedUserId;
    protected $deletedReason;
    protected string $headline;
    protected $history;
    protected $message;
    protected DateTime $modifiedDateTime;
    protected string $modifiedReason;
    protected int $modifiedUserId;
    protected string $movedCountry;
    protected DateTime $movedDateTime;
    protected string $movedLanguage;
    protected int $movedQuestionId;
    protected int $movedUserId;
    protected $questionId;
    protected string $slug;
    protected $subject;

    protected $views;

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    public function getAnswerCountCached(): int
    {
        return $this->answerCountCached;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getCreatedDateTime(): DateTime
    {
        return $this->createdDateTime;
    }

    public function getCreatedIp(): string
    {
        return $this->createdIp;
    }

    public function getCreatedName(): string
    {
        return $this->createdName;
    }

    public function getCreatedUserId(): int
    {
        return $this->createdUserId;
    }

    public function getDeletedDateTime(): DateTime
    {
        return $this->deletedDateTime;
    }

    public function getDeletedUserId(): int
    {
        return $this->deletedUserId;
    }

    public function getDeletedReason(): string
    {
        return $this->deletedReason;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getModifiedDateTime(): DateTime
    {
        return $this->modifiedDateTime;
    }

    public function getModifiedReason(): string
    {
        return $this->modifiedReason;
    }

    public function getModifiedUserId(): int
    {
        return $this->modifiedUserId;
    }

    public function getMovedCountry(): string
    {
        return $this->movedCountry;
    }

    public function getMovedDateTime(): DateTime
    {
        return $this->movedDateTime;
    }

    public function getMovedLanguage(): string
    {
        return $this->movedLanguage;
    }

    public function getMovedQuestionId(): int
    {
        return $this->movedQuestionId;
    }

    public function getMovedUserId(): int
    {
        return $this->movedUserId;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setAnswerCountCached(int $answerCountCached): QuestionEntity\Question
    {
        $this->answerCountCached = $answerCountCached;
        return $this;
    }

    public function setAnswers(array $answers): QuestionEntity\Question
    {
        $this->answers = $answers;
        return $this;
    }

    public function setCreatedDateTime(DateTime $createdDateTime): QuestionEntity\Question
    {
        $this->createdDateTime = $createdDateTime;
        return $this;
    }

    public function setCreatedIp(string $createdIp): QuestionEntity\Question
    {
        $this->createdIp = $createdIp;
        return $this;
    }

    public function setCreatedName(string $createdName): QuestionEntity\Question
    {
        $this->createdName = $createdName;
        return $this;
    }

    public function setCreatedUserId(int $createdUserId): QuestionEntity\Question
    {
        $this->createdUserId = $createdUserId;
        return $this;
    }

    public function setDeletedDateTime(DateTime $deletedDateTime): QuestionEntity\Question
    {
        $this->deletedDateTime = $deletedDateTime;
        return $this;
    }

    public function setDeletedUserId(int $deletedUserId): QuestionEntity\Question
    {
        $this->deletedUserId = $deletedUserId;
        return $this;
    }

    public function setHeadline(string $headline): QuestionEntity\Question
    {
        $this->headline = $headline;
        return $this;
    }

    public function setDeletedReason(string $deletedReason): QuestionEntity\Question
    {
        $this->deletedReason = $deletedReason;
        return $this;
    }

    public function setHistory(array $history): QuestionEntity\Question
    {
        $this->history = $history;
        return $this;
    }

    public function setMessage(string $message): QuestionEntity\Question
    {
        $this->message = $message;
        return $this;
    }

    public function setModifiedDateTime(DateTime $modifiedDateTime): QuestionEntity\Question
    {
        $this->modifiedDateTime = $modifiedDateTime;
        return $this;
    }

    public function setModifiedReason(string $modifiedReason): QuestionEntity\Question
    {
        $this->modifiedReason = $modifiedReason;
        return $this;
    }

    public function setModifiedUserId(int $modifiedUserId): QuestionEntity\Question
    {
        $this->modifiedUserId = $modifiedUserId;
        return $this;
    }

    public function setMovedCountry(string $movedCountry): QuestionEntity\Question
    {
        $this->movedCountry = $movedCountry;
        return $this;
    }

    public function setMovedDateTime(DateTime $movedDateTime) : QuestionEntity\Question
    {
        $this->movedDateTime = $movedDateTime;
        return $this;
    }

    public function setMovedLanguage(string $movedLanguage) : QuestionEntity\Question
    {
        $this->movedLanguage = $movedLanguage;
        return $this;
    }

    public function setMovedQuestionId(int $movedQuestionId) : QuestionEntity\Question
    {
        $this->movedQuestionId = $movedQuestionId;
        return $this;
    }

    public function setMovedUserId(int $movedUserId): QuestionEntity\Question
    {
        $this->movedUserId = $movedUserId;
        return $this;
    }

    public function setQuestionId(int $questionId): QuestionEntity\Question
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function setSlug(string $slug): QuestionEntity\Question
    {
        $this->slug = $slug;
        return $this;
    }

    public function setSubject(string $subject): QuestionEntity\Question
    {
        $this->subject = $subject;
        return $this;
    }

    public function setViews(int $views): QuestionEntity\Question
    {
        $this->views = $views;
        return $this;
    }
}
