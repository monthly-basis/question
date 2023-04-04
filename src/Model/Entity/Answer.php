<?php
namespace MonthlyBasis\Question\Model\Entity;

use DateTime;
use MonthlyBasis\Question\Model\Entity as QuestionEntity;

class Answer extends QuestionEntity\Post
{
    protected $answerId;
    protected $createdDateTime;
    protected $createdIp;
    protected $createdName;
    protected $createdUserId;
    protected $deletedDateTime;
    protected $deletedUserId;
    protected $deletedReason;
    protected int $downVotes;
    protected $history;
    protected $message;
    protected $questionId;
    protected float $rating;
    protected int $upVotes;

    protected $views;

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function __isset(string $name): bool
    {
        return isset($this->$name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->$name = $value;
    }

    public function getAnswerId(): int
    {
        return $this->answerId;
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

    public function getDownVotes(): int
    {
        return $this->downVotes;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getUpVotes(): int
    {
        return $this->upVotes;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setAnswerId(int $answerId): QuestionEntity\Answer
    {
        $this->answerId = $answerId;
        return $this;
    }

    public function setCreatedDateTime(DateTime $createdDateTime): QuestionEntity\Answer
    {
        $this->createdDateTime = $createdDateTime;
        return $this;
    }

    public function setCreatedIp(string $createdIp): QuestionEntity\Answer
    {
        $this->createdIp = $createdIp;
        return $this;
    }

    public function setCreatedUserId(int $createdUserId): QuestionEntity\Answer
    {
        $this->createdUserId = $createdUserId;
        return $this;
    }

    public function setCreatedName(string $createdName): QuestionEntity\Answer
    {
        $this->createdName = $createdName;
        return $this;
    }

    public function setDeletedDateTime(DateTime $deletedDateTime): QuestionEntity\Answer
    {
        $this->deletedDateTime = $deletedDateTime;
        return $this;
    }

    public function setDeletedUserId(int $deletedUserId): QuestionEntity\Answer
    {
        $this->deletedUserId = $deletedUserId;
        return $this;
    }

    public function setDeletedReason(string $deletedReason): QuestionEntity\Answer
    {
        $this->deletedReason = $deletedReason;
        return $this;
    }

    public function setDownVotes(int $downVotes): QuestionEntity\Answer
    {
        $this->downVotes = $downVotes;
        return $this;
    }

    public function setHistory(array $history): QuestionEntity\Answer
    {
        $this->history = $history;
        return $this;
    }

    public function setMessage(string $message): QuestionEntity\Answer
    {
        $this->message = $message;
        return $this;
    }

    public function setQuestionId(int $questionId): QuestionEntity\Answer
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function setRating(float $rating): QuestionEntity\Answer
    {
        $this->rating = $rating;
        return $this;
    }

    public function setUpVotes(int $upVotes): QuestionEntity\Answer
    {
        $this->upVotes = $upVotes;
        return $this;
    }

    public function setViews(int $views): QuestionEntity\Answer
    {
        $this->views = $views;
        return $this;
    }
}
