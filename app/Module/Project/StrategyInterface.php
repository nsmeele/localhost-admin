<?php

namespace Module\Project;

interface StrategyInterface
{
    public function handle(string $projectName, string $targetDirectory): Project;
}
