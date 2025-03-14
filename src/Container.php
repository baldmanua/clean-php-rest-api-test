<?php

namespace App;

use Exception;

class Container
{
    private array $services = [];

    /**
     * @param callable[] $definitions
     */
    public function __construct(array $definitions)
    {
        foreach ($definitions as $key => $definition) {
            $this->services[$key] = $definition($this);
        }
    }

    /**
     * @throws Exception
     */
    public function get(string $id)
    {
        return $this->services[$id] ?? throw new Exception("Service $id not found");
    }
}