<?php
$path  = dirname(ROOT_FOLDER);

if ( isset($_POST['submit']) ) {

	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';	


	// als 'where' is new project
	if ( empty($_POST['project-name']) && $_POST['type'] == 'folder' && $_POST['select-place'] == 'new-project' ) {

		$newProjectName = controlFolderName($path, 'new-project');
		$path .= '/'.$newProjectName;

		createProjectFolder($path, $newProjectName);

	} elseif ( empty($_POST['project-name']) && $_POST['type'] == 'folder' && $_POST['select-place'] == 'subproject' && !empty($_POST['select-project']) ) {

		$path  .= '/'.$_POST['select-project'];
		$newProjectName = controlFolderName($path, 'new-project');
		$path .= '/'.$newProjectName;

		createProjectFolder($path, $newProjectName);

	} elseif ( !empty($_POST['project-name']) && $_POST['type'] == 'folder' && $_POST['select-place'] == 'new-project' ) {

		$newProjectName = controlFolderName($path, $_POST['project-name']);
		$path .= '/' . $newProjectName;

		createProjectFolder($path, $newProjectName);

	} elseif ( !empty($_POST['project-name']) && $_POST['type'] == 'folder' && $_POST['select-place'] == 'subproject' && !empty($_POST['select-project']) ) {

		echo 'Helaas! Functie nog niet beschikbaar';

	} elseif ( ! empty($_POST['project-name']) && $_POST['type'] == 'file' ) {
		$newProjectName = $_POST['project-name'];
		$path .= '/' . $newProjectName;

		$myfile = fopen($path . ".txt", "w") or die("Unable to open file!");
		// $txt = "John Doe\n";
		// fwrite($myfile, $txt);
		// $txt = "Jane Doe\n";
		// fwrite($myfile, $txt);
		fclose($myfile);

	}

}


?>
<h3>Maak een nieuw project aan</h3>
<form method="post" id="new-projects">
	<table class="table">
		<thead>
			<tr>
				<th>Project name</th>
				<th>Type</th>
				<th>Where</th>
				<th>Setup database</th>
			</tr>			
		</thead>
		<tbody>
			<tr>
				<td><input type="text" placeholder="Enter your project name" name="project-name" class="form-control"></td>
				<td>
					<input type="radio" name="type" id="type-folder" value="folder" checked><label for="type-folder">Folder</label><br>
					<input type="radio" name="type" id="type-file" value="file" ><label for="type-file">File</label>
				</td>
				<td>
					<select name="select-place" id="select-place" class="form-control">
						<option value="new-project" selected="">Place in /<?php echo basename(dirname(dirname(__DIR__))); ?></option>
						<option value="subproject">Add to project</option>
						<option value="admin-panel" disabled="">Add to XAMPP Admin Panel</option>
					</select>

					<select name="select-project" id="select-parent-project" style="display:none;" class="form-control">
						<option value="">Select project</option>

						<?php
						$projectFolders = getProjects('root');
						if ( !empty($projectFolders) ) {
							foreach( $projectFolders as $projectName ) {
								echo '<option value="'.$projectName.'">'.$projectName.'</option>';
							}
						}
						?>
					</select>



				</td>
				<td>
					<label class="switch">
						<!-- <input type="checkbox" name="setup-db" id="setup-db" checked=""> -->
						<input type="checkbox" name="setup-db" id="setup-db">
						<div class="slider round"></div>
					</label>
				</td>

			</tr>

			<tr>
				<!-- <td colspan="5"><input type="submit" name="submit" class="btn" value="Create New Project!"></td> -->
				<td colspan="5"><button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i>&nbsp;Add new project</button></td>
			</tr>			
		</tbody>
	</table>
</form>