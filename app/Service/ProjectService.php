<?php

namespace Service;

use Utility\StringUtility;

class ProjectService
{
    public function getProjects(
        ?string $path = null,
        $excludeFiles = false
    ): array {
        $fileSystemService = FileSystemService::getInstance();
        return $fileSystemService->getFolderContents($path, $excludeFiles);
    }

    public function createDatabase($dbName): void
    {
        if (isset($_POST[ 'setup-db' ]) && $_POST[ 'setup-db' ] == true) {
            if ($_POST[ 'select-place' ] == 'new-project') {
                $dbName = StringUtility::replaceWithUnderscore($dbName);
                include_once ROOT_PATH . '/inc/project-admin/setup_database.php';
            } elseif ($_POST[ 'select-place' ] == 'subproject' && ! empty($_POST[ 'select-project' ])) {
                $parentProject = StringUtility::replaceWithUnderscore($_POST[ 'select-project' ]);
                $dbName        = $parentProject . '_' . StringUtility::replaceWithUnderscore($dbName);
                include_once ROOT_PATH . '/inc/project-admin/setup_database.php';
            }
        }
    }
}
