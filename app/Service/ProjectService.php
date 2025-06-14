<?php

namespace Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final readonly class ProjectService
{
    public function getProjectPath(): string
    {
        $fileSystem  = new Filesystem();
        $projectPath = ROOT_PATH . '/projects';
        $fileSystem->mkdir($projectPath, 0755);
        return $projectPath;
    }

    public function listProjects(
        bool $excludeFiles = false
    ): Finder {
        $path = $this->getProjectPath();
        $finder = new Finder();
        if ($excludeFiles) {
            return $finder->directories()->in($path);
        } else {
            return $finder->in($path);
        }
    }
}
