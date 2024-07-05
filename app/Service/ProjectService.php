<?php

namespace Service;

use Utility\StringUtility;

class ProjectService
{
    public function getProjects(string $path, $excludeFiles = false): array
    {
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

    public function createProjectFolder($path, $projectname)
    {
        if (! file_exists($path) && mkdir($path) == true) {
            echo '<p>New folder created: <strong>' . $projectname . '</strong></p>';
            $this->createDatabase($projectname);
            $_POST = '';
        } else {
            echo 'Folder <strong>' . $projectname . '</strong> bestaat al';
        }
    }

    public function controlFolderName($path, $foldername)
    {
        $foldername = preg_replace("/[^a-zA-Z0-9.]/", "-", $foldername);

        $projectFolders   = $this->getProjects($path);
        $newProjectFilter = array ();
        foreach ($projectFolders as $projectFolder) {
            // Check for folders with the name 'new-project', if so, add to array
            if (strpos($projectFolder, $foldername) !== false) {
                $newProjectFilter[] = $projectFolder;
            }
        }

        $newProjectNumber = (! empty($newProjectFilter) ? count($newProjectFilter) + 1 : '');
        $newProjectName   = (! empty($newProjectFilter) ? $foldername . '-' . $newProjectNumber : $foldername);

        return $newProjectName;
    }
}
