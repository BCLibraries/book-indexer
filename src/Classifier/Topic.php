<?php

namespace Bclib\GetBooksFromAlma\Classifier;

class Topic
{
    private string $subject;
    private string $topic;
    private ?string $subtopic;

    public function __construct(string $subject, string $topic, ?string $subtopic = null)
    {
        $this->subject = $subject;
        $this->topic = $topic;
        $this->subtopic = $subtopic;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getSubtopic(): ?string
    {
        return $this->subtopic;
    }

    public function __toString()
    {
        $parts = [$this->subject, $this->topic];
        if ($this->subtopic) {
            $parts[] = $this->subtopic;
        }
        return join(':', $parts);
    }


}