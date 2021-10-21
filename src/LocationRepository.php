<?php

namespace Bclib\GetBooksFromAlma;

use Bclib\GetBooksFromAlma\Models\Location;

class LocationRepository
{
    /** @var Location[][] */
    private array $locations = [];

    public function lookup(string $library_code, string $location_code): Location
    {
        return $this->locations[$library_code][$location_code];
    }

    public function addLocation(Location $location): void
    {
        $library_code = $location->getLibrary()->getCode();
        $location_code = $location->getCode();

        if (!isset($this->locations[$library_code])) {
            $this->locations[$library_code] = [];
        }

        $this->locations[$library_code][$location_code] = $location;
    }
}