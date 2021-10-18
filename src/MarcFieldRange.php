<?php

namespace Bclib\GetBooksFromAlma;

use Bclib\GetBooksFromAlma\Exceptions\BadMARCRangeException;

class MarcFieldRange
{
    private string $value;

    public const GENERAL = '0xx';
    public const MAIN_ENTRY = '1xx';
    public const TITLE = '2xx';
    public const PHYSICAL_FORM = '3xx';
    public const SERIES = '4xx';
    public const NOTES = '5xx';
    public const SUBJECTS = '6xx';
    public const ADDED_ENTRIES = '7xx';
    public const ADDED_ACCESS = '8xx';
    public const LOCAL = '9xx';

    private const VALID_RANGES = [
        self::GENERAL,
        self::MAIN_ENTRY,
        self::TITLE,
        self::PHYSICAL_FORM,
        self::SERIES,
        self::NOTES,
        self::SUBJECTS,
        self::ADDED_ENTRIES,
        self::ADDED_ACCESS,
        self::LOCAL
    ];

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_RANGES)) {
            throw new BadMARCRangeException("$value is not a valid MARC range");
        }
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }


}