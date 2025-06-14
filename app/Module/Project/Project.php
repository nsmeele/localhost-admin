<?php

namespace Module\Project;

final readonly class Project
{

    public function __construct(
        private StrategyInterface $strategy,
        private string $path,
        private string $name,
    ) {
    }

}