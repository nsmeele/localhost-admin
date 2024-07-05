<?php

function debug($array)
{
    return '<pre>'.print_r($array, true).'</pre>';
}

function getProjects($path)
{
    if (empty($path) || $path == 'root') {
        $path = dirname(ROOT_PATH);
    }

    $projectFolders = array_diff(scandir($path), array ('.', '..',));
    // Remove files, we want only our main directories here
//    $projectFolders = array_filter($projectFolders, 'is_dir');
    /// Reset array
    $projectFolders = array_values($projectFolders);

    return $projectFolders;
}

function replaceWithUnderscore($string)
{
    // verwijder spaties voor en na string
    $string = trim($string);
    // Verwijder speciale tekens en vervang door underscore
    $string = preg_replace("/[^a-zA-Z0-9.]/", "_", $string);
    return $string;
}

function createDatabase($dbName)
{
    if (isset($_POST[ 'setup-db' ]) && $_POST[ 'setup-db' ] == true) {
        if ($_POST[ 'select-place' ] == 'new-project') {
            $dbName = replaceWithUnderscore($dbName);
            include_once(ROOT_DIR.'/inc/project-admin/setup_database.php');
        } elseif ($_POST[ 'select-place' ] == 'subproject' && ! empty($_POST[ 'select-project' ])) {
            $parentProject = replaceWithUnderscore($_POST[ 'select-project' ]);
            $dbName        = $parentProject.'_'.replaceWithUnderscore($dbName);
            include_once(ROOT_DIR.'/inc/project-admin/setup_database.php');
        }

    }
    return;
}

function createProjectFolder($path, $projectname)
{

    if (! file_exists($path) && mkdir($path) == true) {
        echo '<p>New folder created: <strong>'.$projectname.'</strong></p>';
        createDatabase($projectname);
        $_POST = '';
    } else {
        echo 'Folder <strong>'.$projectname.'</strong> bestaat al';
    }

    return;
}

function controlFolderName($path, $foldername)
{

    $foldername = preg_replace("/[^a-zA-Z0-9.]/", "-", $foldername);

    $projectFolders   = getProjects($path);
    $newProjectFilter = array ();
    foreach ($projectFolders as $projectFolder) {
        // Check for folders with the name 'new-project', if so, add to array
        if (strpos($projectFolder, $foldername) !== false) {
            $newProjectFilter[] = $projectFolder;
        }
    }

    $newProjectNumber = (! empty($newProjectFilter) ? count($newProjectFilter) + 1 : '');
    $newProjectName   = (! empty($newProjectFilter) ? $foldername.'-'.$newProjectNumber : $foldername);

    return $newProjectName;

}

function getCurrentFilePath()
{
    $page = trim($_SERVER[ 'REQUEST_URI' ], '/');
    $file = ROOT_PATH.'/pages/'.$page.'.php';

    if (! file_exists($file) && file_exists(ROOT_PATH.'/pages/'.$page.'/index.php')) {
        $file = ROOT_PATH.'/pages/'.$page.'/index.php';
    }

    return $file;
}

function getBreadcrumbMenu()
{
    $path       = $_SERVER[ 'REQUEST_URI' ];
    $parts      = explode('/', $path);
    $lastPart   = end($parts);
    $basePath   = '';
    $parentPath = $basePath;
    $html       = '';

    if ($path === '/') {
        return '';
    }

    if (! empty($parts)) {
        foreach ($parts as $part) {
            $itemLabel   = ucfirst(strtolower($part));
            $itemContent = '<a href="'.$parentPath.'/'.$part.'">'.$itemLabel.'</a>';

            if (empty($part)) {
                $itemContent = '<a href="/"><i class="fas fa-home"></i></a>';
            }

            if ($part === $lastPart) {
                $itemContent = $itemLabel;
            }

            $html .= sprintf('<li class="breadcrumb-item">%s</li>', $itemContent);

            $parentPath .= '/'.$part;
        }
    }

    return sprintf('<ol class="breadcrumb">%s</ol>', $html);

}