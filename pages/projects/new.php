<?php
$path = ROOT_PATH;

$projectService = new \Service\ProjectService();

if (isset($_POST[ 'submit' ])) {
    // als 'where' is new project
    if (empty($_POST[ 'project-name' ]) && $_POST[ 'type' ] == 'folder' && $_POST[ 'select-place' ] == 'new-project') {
        $newProjectName = $projectService->controlFolderName($path, 'new-project');
        $path           .= '/' . $newProjectName;

        $projectService->createProjectFolder($path, $newProjectName);
    } elseif (empty($_POST[ 'project-name' ]) && $_POST[ 'type' ] == 'folder' && $_POST[ 'select-place' ] == 'subproject' && ! empty($_POST[ 'select-project' ])) {
        $path           .= '/' . $_POST[ 'select-project' ];
        $newProjectName = $projectService->controlFolderName($path, 'new-project');
        $path           .= '/' . $newProjectName;

        $projectService->createProjectFolder($path, $newProjectName);
    } elseif (! empty($_POST[ 'project-name' ]) && $_POST[ 'type' ] == 'folder' && $_POST[ 'select-place' ] == 'new-project') {
        $newProjectName = $projectService->controlFolderName($path, $_POST[ 'project-name' ]);
        $path           .= '/' . $newProjectName;

        $projectService->createProjectFolder($path, $newProjectName);
    } elseif (! empty($_POST[ 'project-name' ]) && $_POST[ 'type' ] == 'folder' && $_POST[ 'select-place' ] == 'subproject' && ! empty($_POST[ 'select-project' ])) {
        echo 'Helaas! Functie nog niet beschikbaar';
    } elseif (! empty($_POST[ 'project-name' ]) && $_POST[ 'type' ] == 'file') {
        $newProjectName = $_POST[ 'project-name' ];
        $path           .= '/' . $newProjectName;

        $myfile = fopen($path . ".txt", "w") or die("Unable to open file!");
        // $txt = "John Doe\n";
        // fwrite($myfile, $txt);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        fclose($myfile);
    }
}


?>
<h2>Create new project</h2>
<form method="post" id="new-projects">
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Type</th>
            <th>Where</th>
            <th>Setup database</th>
        </tr>
        </thead>
        <tbody>
        <tr class="table-light">
            <td><input type="text" placeholder="Enter your project name" name="project-name" class="form-control"/></td>
            <td>
                <?php
                echo new \Component\Form\Field\InputRadio([
                    'name' => 'type', 'value' => 'folder', 'label' => 'Folder', 'id' => 'select-folder'
                ]);
                echo new \Component\Form\Field\InputRadio([
                    'name' => 'type', 'value' => 'file', 'label' => 'File', 'id' => 'select-file'
                ]);
                ?>
            </td>
            <td>
                <select name="select-place" id="select-place" class="form-select">
                    <option value="new-project" selected="">Place in
                        /<?php echo basename(dirname(dirname(__DIR__))); ?></option>
                    <option value="subproject">Add to project</option>
                    <option value="admin-panel" disabled="">Add to XAMPP Admin Panel</option>
                </select>
                <div class="d-none">
                    <label for="exampleDataList" class="form-label">Datalist example</label>
                    <input class="form-control" name="parent-project" list="datalistOptions" id="exampleDataList" placeholder="Select project">
                    <datalist id="datalistOptions">
                        <?php
                        $projectFolders = $projectService->getProjects('root');
                        if (! empty($projectFolders)) {
                            foreach ($projectFolders as $projectName) {
                                echo '<option value="' . $projectName . '">' . $projectName . '</option>';
                            }
                        }
                        ?>
                    </datalist>

                </div>

            </td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" name="setup-db" type="checkbox" role="switch"/>
                </div>
            </td>

        </tr>
        </tbody>
        <tfoot>
        <tr>
            <!-- <td colspan="5"><input type="submit" name="submit" class="btn" value="Create New Project!"></td> -->
            <td colspan="5">
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="fa-solid fa-plus-circle"></i>&nbsp;Add project
                </button>
            </td>
        </tr>
        </tfoot>
    </table>
</form>