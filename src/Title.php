<?php

namespace Bclib\GetBooksFromAlma;

class Title
{
    private string $value;

    public function __construct(string $value)
    {

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }


}