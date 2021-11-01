<?php

namespace Bclib\GetBooksFromAlma\Classifier;

class CallNumber
{
    private const REGEX = /** @lang RegExp */
        <<<'REGEX'
/^\s*
(?:VIDEO-D)?                  # for video stuff
(?:DVD-ROM)?                  # DVDs, obviously
(?:CD-ROM)?                   # CDs
(?:TAPE-C)?                   # Tapes
\s*
([A-Z]{1,3})                  # alpha
\s*
(?:                           # optional numbers with optional decimal point
  (\d+)
  (?:\s*?\.\s*?(\d+))?
)?
\s*
(?:                           # optional cutter
  \.? \s*
  ([A-Z])                     # cutter letter
  \s*
  (\d+ | \Z)                  # cutter numbers
)?
\s*
(?:                           # optional cutter
  \.? \s*
  ([A-Z])                     # cutter letter
  \s*
  (\d+ | \Z)                  # cutter numbers
)?
\s*
(?:                           # optional cutter
  \.? \s*
  ([A-Z])                     # cutter letter
  \s*
  (\d+ | \Z)                  # cutter numbers
)?
(\s+.+?)?                     # everything else
\s*$/xi
REGEX;

    public string $val;
    public string $normalized;

    public function __construct(string $call_number)
    {
        $call_number = strtolower($call_number);
        //$this->val = 'a';
        $this->normalized = self::normalizeCallNumber($call_number);
    }

    public function isLessThan(CallNumber $other_call_number): bool
    {
        return strcmp($this->normalized, $other_call_number->normalized) < 0;
    }

    public function isEqualTo(CallNumber $other_call_number): bool
    {
        return strcmp($this->normalized, $other_call_number->normalized) === 0;
    }

    private static function normalizeCallNumber(string $call_number): string
    {
        $matches = [];
        $match_found = preg_match(self::REGEX, $call_number, $matches);

        if (!$match_found) {
            throw new \Exception("$call_number is not a call number!\n");
        }

        $padded_matches = [];

        $padded_matches[0] = '';
        $match = $matches[1] ?? '';

        $padded_matches[1] = str_pad($match, 4, ' ', STR_PAD_RIGHT);

        // Left pad everything with spaces.
        for ($i = 2; $i < 11; $i++) {
            $match = $matches[$i] ?? '';
            $padded_matches[$i] = str_pad($match, 4, ' ', STR_PAD_LEFT);
        }

        return join('', $padded_matches);
    }
}