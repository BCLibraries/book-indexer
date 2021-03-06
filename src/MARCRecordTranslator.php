<?php

namespace Bclib\GetBooksFromAlma;

use Bclib\GetBooksFromAlma\Models\Author;
use Bclib\GetBooksFromAlma\Models\Datafield;
use Bclib\GetBooksFromAlma\Models\Item;
use Bclib\GetBooksFromAlma\Models\MarcFieldRange;
use Bclib\GetBooksFromAlma\Models\Record;
use Bclib\GetBooksFromAlma\Models\Subject;
use Bclib\GetBooksFromAlma\Models\Tag;
use Bclib\GetBooksFromAlma\Models\Title;

class MARCRecordTranslator
{
    private LocationRepository $locations;
    private TagRepository $tags;

    public function __construct(LocationRepository $locations, TagRepository $tags)
    {
        $this->locations = $locations;
        $this->tags = $tags;
    }

    public function build(Record $record): Item
    {
        $item = new Item();

        $this->addTitles($record, $item);
        $this->addSubjects($record, $item);
        $this->addAuthors($record, $item);
        $this->addLocations($record, $item);

        return $item;
    }

    private function addTitles(Record $record, Item $item)
    {
        $this->addFieldType($record, $item, $this->tags->getTitleTags(), 'addTitle');
    }

    private function addAuthors(Record $record, Item $item)
    {
        $this->addFieldType($record, $item, $this->tags->getPersonalNameTags(), 'addPersonalNameAuthor');
        $this->addFieldType($record, $item, $this->tags->getCorporateNameTags(), 'addCorporateNameAuthor');
        $this->addFieldType($record, $item, $this->tags->getConferenceNameTags(), 'addConferenceNameAuthor');
    }

    private function addSubjects(Record $record, Item $item): void
    {
        $subject_range = new MarcFieldRange(MarcFieldRange::SUBJECTS);
        $subject_fields = $record->getDatafieldsByTagRanges($subject_range);

        foreach ($subject_fields as $subject_field) {
            $this->addSubject($subject_field, $item);
        }
    }

    private function addLocations(Record $record, Item $item): void
    {
        $location_fields = $record->getDatafieldsByTag(new Tag('954'));
        foreach ($location_fields as $location_field) {
            $library_codes = $location_field->getSubfieldValuesByCode('o');
            $location_codes = $location_field->getSubfieldValuesByCode('r');

            if (sizeof($library_codes) > 0 && sizeof($location_codes) > 0) {
                $location = $this->locations->lookup($library_codes[0], $location_codes[0]);
                $item->addLocation($location);
            }
        }
    }

    private function addSubject(Datafield $subject_field, Item $item): void
    {
        $components = $subject_field->getSubfieldsByCode('a');
        $components = array_merge($components, $subject_field->getSubfieldValuesByCode('x'));
        $components = array_merge($components, $subject_field->getSubfieldValuesByCode('z'));
        $components = array_merge($components, $subject_field->getSubfieldValuesByCode('d'));
        $components = array_merge($components, $subject_field->getSubfieldValuesByCode('y'));
        $subject = new Subject($components);
        $item->addSubject($subject);
    }

    private function addPersonalNameAuthor(Datafield $main_entry_field, Item $item): void
    {
        $author_string = $this->buildStringFromSubfields($main_entry_field, ['a']);
        if ($author_string !== '') {
            $item->addAuthor(new Author($author_string));
        }
    }

    private function addCorporateNameAuthor(Datafield $corp_main_entry_field, Item $item): void
    {
        $author_string = $this->buildStringFromSubfields($corp_main_entry_field, ['a', 'b']);

        if ($author_string !== '') {
            $item->addAuthor(new Author($author_string));
        }
    }

    private function addConferenceNameAuthor(Datafield $corp_main_entry_field, Item $item): void
    {
        $author_string = $this->buildStringFromSubfields($corp_main_entry_field, ['a', 'c', 'e', 'q']);

        if ($author_string !== '') {
            $item->addAuthor(new Author($author_string));
        }
    }

    private function addTitle(Datafield $title_field, Item $item): void
    {
        $title_string = $this->buildStringFromSubfields($title_field, ['a', 'b']);

        if ($title_string !== '') {
            $item->addTitle(new Title($title_string));
        }
    }

    /**
     * @param Datafield $entry_field
     * @param array $subfield_codes
     * @return string
     */
    private function buildStringFromSubfields(Datafield $entry_field, array $subfield_codes): string
    {
        $string_parts = [];

        foreach ($subfield_codes as $code) {
            $subfield_strings = $entry_field->getSubfieldValuesByCode($code);
            $string_parts = array_merge($string_parts, $subfield_strings);
        }

        return implode(' ', $string_parts);
    }

    /**
     * @param Record $record
     * @param Item $item
     * @param Tag[] $tags
     * @param string $add_method
     */
    private function addFieldType(Record $record, Item $item, array $tags, string $add_method)
    {
        foreach ($tags as $tag) {
            foreach ($record->getDatafieldsByTag($tag) as $datafield) {
                $this->{$add_method}($datafield, $item);
            }
        }
    }
}