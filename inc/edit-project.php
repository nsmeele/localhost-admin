<?php
$files = getProjects('root');

// Delete folder, check if sure
if ( isset($_POST['select-folder']) && !isset($_POST['delete-submit-sure'] ) ) {

	echo '<div class="alert alert-danger"><i class="fa fa-trash-o fa-lg"></i> Are you sure you want to delete these folders?</div>';
	echo '<form method="post">';
		if ( is_array($_POST['select-folder']) ) {
			// echo debug($_POST['select-folder']);
			foreach( $_POST['select-folder'] as $folder => $status ) : ?>
				<div class="form-check">
					<label class="form-check-label">
						<input class="form-check-input" type="checkbox" name="delete-folder[<?= $folder; ?>]" checked="">
						<?= $folder; ?>
					</label>
				</div>
			<?php endforeach;
		}		
		echo '<input type="submit" name="delete-folder-submit" value="Delete!" class="btn btn-primary">';
	echo '</form><br>';
// If sure, delete folder
} elseif ( isset($_POST['delete-folder-submit']) && !empty($_POST['delete-folder']) ) {

	if ( is_array($_POST['delete-folder']) ) {
		foreach( $_POST['delete-folder'] as $folder => $status ) {
			$filename = $path . '/' . $folder;
			if (!is_dir($filename)) {
			    mkdir($filename);
			}

			if ( rmdir($filename) == true ) {
				echo '<p>Folder <u>'.$folder.'</u> has succesfully been deleted</p>';
				$folderKey = array_search($folder, $files);
				unset($files[$folderKey]);
				$_POST = array();
			}

		}
	}

}


foreach ( $files as $folderKey => $folderName ) {

	$postFolderName = strtolower($folderName);
	$postFolderName = str_replace(' ', '-', $postFolderName);
	$filename = $path . '/' . $folderName;

	// Edit folder name
	if ( !empty($_POST['rename'][$folderName]) ) {

		echo '<p>True</p>';

		// $newName = $_POST['rename'][$folderName];
		// $newName = strtolower($newName);
		// $newName = str_replace(' ', '-', $newName);

		// if ( file_exists( $filename ) ) {
		// 	$newFolderName = rename($filename, $newName);
			
		// 	if ( $newFolderName === true ) {
		// 		echo '<p>' . $folderName.' heeft de nieuwe naam "' . $newName . '" gekregen</p>';
		// 		$_POST = array();
		// 	}

		// }

	}


} // end foreach

echo '<h3>Edit projects&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i></h3>';
echo '<form id="edit-projects" method="post">';
	echo '<table class="table table-hover"><thead>';
	// Titles
	echo '<tr>';
		echo '<th style="max-width: 15px; text-align: center;"><input type="checkbox" name="select_all" title="Select all projects"></th>';
		echo '<th>Project name</th>';
		// echo '<th>Rename</th>';
		echo '<th>Last modified</th>';
		echo '<th></th>';	
	echo '</tr></thead><tbody>';

	foreach ( $files as $projectKey => $projectName ) {

		// Item info
		$folderName = strtolower($projectName);
		$folderName = str_replace(' ', '-', $folderName);

		echo '<tr>';
			echo '<td style="max-width: 15px; text-align: center;"><input type="checkbox" name="select-folder['.$folderName.']"></td>';
			echo '<td><a class="text-dark" href="/'.$projectName.'" target="_blank">'.$projectName.'</a></td>';
			// echo '<td><input type="text" value="" name="'.$folderName.'[change-folder]" class="form-control" placeholder="Enter a new project name"></td>';			
			echo '<td>';

				$folder = $path . '/' . $projectName;
				$folderModified = array();
				if (file_exists($folder)) {
					$folderModified['date'] = date("d/m/Y", filemtime($folder));
					$folderModified['date-start'] = date("Y-m-d", filemtime($folder));
					$folderModified['time'] = date("H:i:s", filemtime($folder));


					$startTimeStamp = strtotime($folderModified['date-start']);
					$endTimeStamp = strtotime( date("Y-m-d") );

					$timeDiff = abs($endTimeStamp - $startTimeStamp);

					$numberDays = $timeDiff/86400;  // 86400 seconds in one day

					// and you might want to convert to integer
					$numberDays = intval($numberDays);
					
					echo '<span class="modified-date">' . $folderModified['date'] . '</span><span class="modified-time">om '.$folderModified['time'].'</span><span class="ago">('.$numberDays.' days ago)</span>';

				}		

			echo '</td>'; ?>
			<td class="text-right">
<!-- 				<select name="rename[<?= $folderName; ?>]" class="form-control" onchange="this.form.submit();">
					<option value="">Selecteer actie</option>
					<option>Rename</option>
				</select>
 -->
 				<a href="" onclick="toggleMenu(this);">&nbsp;<i class="fa fa-angle-down"></i></a>
 				<div class="toggle-menu position-absolute">
 					<ul class="list-unstyled bg-white py-1 px-3">
 						<li><a href="">Rename</a></li>
 						<li><a href="">Rename</a></li>
 						<li><a href="">Rename</a></li>
 						<li><a href="">Rename</a></li>
 					</ul>
 				</div>

			</td>



		<?php echo '</tr>';

	} // end foreach

		echo '<tr class="no-border">';
			// echo '<td colspan="2"></td>';
			echo '<td colspan="6">';
			echo '<button name="delete-multiple" type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i>&nbsp;Delete selected</button>';
			echo '</td>';
		echo '</tr>';


	echo '</tbody></table>';


echo '</form>';