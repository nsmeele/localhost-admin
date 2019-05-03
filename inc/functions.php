<?php

function debug($array) {
	return '<pre>'.print_r($array, true).'</pre>';
}

function getProjects($path) {
	if ( empty($path) || $path == 'root' ) {
		$path  = dirname(ROOT_FOLDER);
	}

	$projectFolders = array_diff(scandir($path), array('.', '..', 'local-admin'));
	// Remove files, we want only our main directories here
	$projectFolders = array_filter($projectFolders, 'is_dir');
	/// Reset array
	$projectFolders = array_values($projectFolders);

	return $projectFolders;	
}

function getFiles($path) {
	if ( empty($path) || $path == 'root' ) {
		$path  = dirname(ROOT_FOLDER);
	}

	$files = array_diff(scandir($path), array('.', '..'));
	$files = array_filter(glob($path . '/*'));
	return $files;	
}

function replaceWithUnderscore($string) {
	// verwijder spaties voor en na string
	$string = trim($string);
	// Verwijder speciale tekens en vervang door underscore
	$string = preg_replace("/[^a-zA-Z0-9.]/", "_", $string);
	return $string;
}

function createDatabase($dbName) {
	if ( isset($_POST['setup-db']) && $_POST['setup-db'] == true ) {
		if ( $_POST['select-place'] == 'new-project' ) {
			$dbName = replaceWithUnderscore($dbName);			
			include_once(ROOT_DIR.'/inc/project-admin/setup_database.php');
		} elseif ( $_POST['select-place'] == 'subproject' && !empty($_POST['select-project']) ) {
			$parentProject = replaceWithUnderscore($_POST['select-project']);
			$dbName = $parentProject.'_'.replaceWithUnderscore($dbName);
			include_once(ROOT_DIR.'/inc/project-admin/setup_database.php');
		}

	}
	return;
}

function createProjectFolder($path, $projectname) {

	if (!file_exists($path) && mkdir($path) == true ) {
		echo '<p>New folder created: <strong>' . $projectname . '</strong></p>';
		createDatabase($projectname);
		$_POST = '';
	} else {
		echo 'Folder <strong>'.$projectname.'</strong> bestaat al';
	}

	return;
}

function controlFolderName($path, $foldername) {

	$foldername = preg_replace("/[^a-zA-Z0-9.]/", "-", $foldername);

	$projectFolders = getProjects($path);
	$newProjectFilter = array();
	foreach ( $projectFolders as $projectFolder ) {
		// Check for folders with the name 'new-project', if so, add to array
		if (strpos($projectFolder, $foldername) !== false) {
			$newProjectFilter[] = $projectFolder;
		}
	}

	$newProjectNumber = ( ! empty($newProjectFilter) ? count( $newProjectFilter ) + 1 : '' );
	$newProjectName = ( ! empty($newProjectFilter) ? $foldername.'-'.$newProjectNumber : $foldername );

	return $newProjectName;

}