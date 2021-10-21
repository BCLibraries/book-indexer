<?php

namespace Bclib\GetBooksFromAlma\Models;

class Datafield
{
    private Tag $tag;
    private string $ind1;
    private string $ind2;

    /** @var Subfield[][] */
    private array $subfields_by_code = [];

    /**
     * @param Tag $tag
     * @param string|null $ind1
     * @param string|null $ind2
     * @param Subfield[] $subfields
     */
    public function __construct(Tag $tag, string $ind1 = null, string $ind2 = null, array $subfields = [])
    {
        $this->tag = $tag;
        $this->ind1 = $ind1;
        $this->ind2 = $ind2;

        foreach ($subfields as $subfield) {
            $this->addSubfield($subfield);
        }
    }

    private function addSubfield(Subfield $subfield)
    {
        $key = $subfield->getCode();

        if (isset($this->subfields_by_code[$key])) {
            $this->subfields_by_code[$key][] = $subfield;
        } else {
            $this->subfields_by_code[$key] = [$subfield];
        }
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function getInd1(): string
    {
        return (string)$this->ind1;
    }

    public function getInd2(): string
    {
        return (string)$this->ind2;
    }

    /**
     * @return Subfield[]
     */
    public function getSubfieldsByCode(string $code): array
    {
        return $this->subfields_by_code[$code] ?? [];
    }

    /**
     * @param string $code
     * @return string[]
     */
    public function getSubfieldValuesByCode(string $code): array
    {
        return array_map(function (Subfield $subfield) {
            return $subfield->getValue();
        }, $this->getSubfieldsByCode($code));
    }
}