<?php

namespace Module\Project\Strategy;

use Module\Project\Project;
use Module\Project\StrategyInterface;
use Symfony\Component\Filesystem\Filesystem;
use Utility\StringUtility;

class EmptyDirectoryStrategy implements StrategyInterface
{


    public function handle(
        string $projectName,
        string $targetDirectory,
    ) : Project {
        $fileSystem      = new Filesystem();
        $projectSafeName = strtolower(StringUtility::replaceWithUnderscore($projectName));
        $projectPath     = $targetDirectory.'/'.$projectSafeName;

        if ($fileSystem->exists($projectPath)) {
            throw new \RuntimeException(
                sprintf('Project directory "%s" already exists.', $projectPath)
            );
        }

        $fileSystem->mkdir($projectPath, 0755);

        return new Project(
            strategy: $this, path: $projectPath, name: $projectName,
        );
    }
}