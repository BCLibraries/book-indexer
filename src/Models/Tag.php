<?php

namespace Bclib\GetBooksFromAlma\Models;

use Bclib\GetBooksFromAlma\Exceptions\BadTagException;

class Tag
{
    private string $value;

    /**
     * @throws BadTagException
     */
    public function __construct(string $value)
    {
        if (preg_match('/^\d\d\d/', $value) !== 1) {
            throw new BadTagException("$value is not a valid MARC tag");
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getRange(): MarcFieldRange
    {
        $range_string = substr($this->value, 0, 1) . "xx";
        return new MarcFieldRange($range_string);
    }

}