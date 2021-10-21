<?php

namespace Bclib\GetBooksFromAlma\Models;

class Record
{
    /** @var Datafield[][] */
    private array $datafields_by_tag = [];

    /** @var Datafield[][] */
    private array $datafields_by_range = [];

    public function addDatafield(Datafield $datafield): void
    {
        $tag = $datafield->getTag();

        // We store datafields by tag...
        if (isset($this->datafields_by_tag["$tag"])) {
            $this->datafields_by_tag["$tag"][] = $datafield;
        } else {
            $this->datafields_by_tag["$tag"] = [$datafield];
        }

        // ...and by tag range.
        $range = "{$tag->getRange()}";
        if (isset($this->datafields_by_range[$range])) {
            $this->datafields_by_range[$range][] = $datafield;
        } else {
            $this->datafields_by_range[$range] = [$datafield];
        }
    }

    /**
     * @return Datafield[]
     */
    public function getDatafields(): array
    {
        return $this->datafields_by_tag;
    }

    /**
     * @param Tag $tag
     * @return Datafield[]
     */
    public function getDatafieldsByTag(Tag $tag): array
    {
        return $this->datafields_by_tag["$tag"] ?? [];
    }

    /**
     * @param MarcFieldRange $range
     * @return Datafield[]
     */
    public function getDatafieldsByTagRanges(MarcFieldRange $range): array
    {
        return $this->datafields_by_range["$range"] ?? [];
    }
}