<?php

namespace Bclib\GetBooksFromAlma;

class LibraryRepository
{
    private array $entries = [];

    public function __construct()
    {
    }

    public function lookupLibrary(string $library_code): Library
    {
        return $this->entries[$library_code]->library;
    }

    public function lookupLocation(string $library_code, string $location_code): Location
    {
        return $this->entries[$library_code]->locations;
    }

    /**
     * @param Library $library
     * @param LocationRepository $locations
     */
    public function addLibrary(Library $library, LocationRepository $locations): void
    {
        $entry = new \stdClass();
        $entry->library = $library;
        $entry->locations = $locations;
        $this->entries[$library->getCode()] = $entry;
    }
}