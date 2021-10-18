<?php

namespace Bclib\GetBooksFromAlma;

class Item
{
    /** @var Subject[] */
    private array $subjects = [];

    /** @var Author[] */
    private array $authors = [];

    /** @var Title[] */
    private array $titles = [];

    /** @var Library[] */
    private array $libraries = [];

    /** @var Location[] */
    private array $locations = [];

    public function addSubject(Subject $subject)
    {
        $this->addIfNotPresent($subject, $this->subjects);
    }

    /**
     * @return Subject[]
     */
    public function getSubjects(): array
    {
        return $this->subjects;
    }

    public function addAuthor(Author $author): void
    {
        $this->addIfNotPresent($author, $this->authors);
    }

    /**
     * @return Author[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function addTitle(Title $title): void
    {
        $this->addIfNotPresent($title, $this->titles);
    }

    /**
     * @return Title[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    public function addLibrary(Library $library): void
    {
        $this->addIfNotPresent($library, $this->libraries);
    }

    /**
     * @return Library[]
     */
    public function getLibraries(): array
    {
        return $this->libraries;
    }

    public function addLocation(Location $location): void
    {
        $this->addIfNotPresent($location, $this->locations);
    }

    /**
     * @return Location[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    private function addIfNotPresent($value, array &$array): void
    {
        if (!in_array($value, $array)) {
            $array[] = $value;
        }
    }
}