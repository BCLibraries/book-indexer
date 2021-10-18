<?php

namespace Bclib\GetBooksFromAlma;

class Subject
{
    /** @var string[] */
    private array $components;

    public function __construct(array $components)
    {
        $this->components = $this->normalize($components);
    }

    public function getValue(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return implode(' -- ', $this->components);
    }

    /**
     * @param string[] $components
     * @return string[]
     */
    private function normalize(array $components): array
    {
        $components_out = [];

        foreach ($components as $component) {
            $components_out[] = trim($component, " \ \t\n\r\0\x0B.,");
        }

        return $components_out;
    }

}