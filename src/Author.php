<?php

namespace Bclib\GetBooksFromAlma;

class Author
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $this->normalize($name);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    private function normalize(string $name): string
    {
        # Strip trailing punctuation.
        return trim($name, " \ \t\n\r\0\x0B.,");
    }

}