<?php

namespace Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Utility\StringUtility;

class ProjectService
{
    public function getProjects(
        string $path,
        bool $excludeFiles = false
    ): Finder {
        $finder = new Finder();
        if ($excludeFiles) {
            return $finder->directories()->in($path);
        } else {
            return $finder->in($path);
        }
    }

    public function createDatabase(string $dbName): void
    {
        if (empty($dbName)) {
            throw new \InvalidArgumentException('Database name cannot be empty');
        }

        /**
         * @var \Symfony\Component\HttpFoundation\Request $request
         */
        global $request;

        if ((int)$request->get('setup-db')) {
            if ($request->get('select-place') == 'new-project') {
                $dbName = StringUtility::replaceWithUnderscore($dbName);
                include_once ROOT_PATH . '/inc/project-admin/setup_database.php';
            } elseif ($request->get('select-place') == 'subproject' && ! empty($request->get('select-project'))) {
                $parentProject = StringUtility::replaceWithUnderscore($request->get('select-project'));
                $dbName        = $parentProject . '_' . StringUtility::replaceWithUnderscore($dbName);
            }
        }
    }
}
