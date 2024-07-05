<?php

namespace Trait;

trait DynamicPropertiesTrait
{
    protected array $properties = [];

    public function __construct(array $properties = array ())
    {
        $this->properties = array_merge($this->getDefaultProperties(), $properties);
    }

    public function __get(string $name)
    {
        if (isset($this->properties[ $name ])) {
            return $this->properties[ $name ];
        }
    }

    public function __set(string $name, $value): void
    {
        $this->properties[ $name ] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->properties);
    }

    protected function getDefaultProperties(): array
    {
        return [];
    }
}
