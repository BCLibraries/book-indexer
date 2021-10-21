<?php

namespace Bclib\GetBooksFromAlma;

use Bclib\GetBooksFromAlma\Models\Datafield;
use Bclib\GetBooksFromAlma\Models\Record;
use Bclib\GetBooksFromAlma\Models\Subfield;
use Bclib\GetBooksFromAlma\Models\Tag;

class RecordTranslator
{
    public function build(\SimpleXMLElement $record_xml): Record
    {
        $record = new Record();

        // If the record doesn't have anything in it, give up.
        if (!$record_xml->metadata->record) {
            return $record;
        }

        foreach ($record_xml->metadata->record->datafield as $datafield_xml) {
            $datafield = $this->buildDatafield($datafield_xml);
            $record->addDatafield($datafield);
        }

        return $record;
    }

    /**
     * @param \SimpleXMLElement $datafield_xml
     * @return Datafield
     * @throws Exceptions\BadTagException
     */
    private function buildDatafield(\SimpleXMLElement $datafield_xml): Datafield
    {
        // Get the easy stuff.
        $tag = new Tag((string)$datafield_xml['tag']);
        $ind1 = (string)$datafield_xml['ind1'];
        $ind2 = (string)$datafield_xml['ind2'];

        // Build subfields.
        $subfields = [];
        foreach ($datafield_xml->subfield as $subfield_xml) {
            $subfields[] = $this->buildSubfield($subfield_xml);
        }

        return new Datafield($tag, $ind1, $ind2, $subfields);
    }

    private function buildSubfield(\SimpleXMLElement $subfield_xml): Subfield
    {
        return new Subfield((string)$subfield_xml['code'], (string)$subfield_xml);
    }
}