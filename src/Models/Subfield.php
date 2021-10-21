<?php

namespace Bclib\GetBooksFromAlma\Models;

class Subfield
{
    private string $code;
    private string $value;

    public function __construct(string $code, string $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }


}