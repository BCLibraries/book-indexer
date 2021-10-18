<?php

namespace Bclib\GetBooksFromAlma;

class Location
{
    private string $code;
    private string $name;
    private string $external_name;
    private Library $library;

    public function __construct(string $code, string $name, string $external_name, Library $library)
    {
        $this->code = $code;
        $this->name = $name;
        $this->external_name = $external_name;
        $this->library = $library;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExternalName(): string
    {
        return $this->external_name;
    }

    public function getLibrary(): Library
    {
        return $this->library;
    }
}