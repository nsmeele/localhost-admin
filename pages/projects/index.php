<?php
$path = ROOT_PATH;

$projectService = new \Service\ProjectService();

$files = $projectService->getProjects('root', true);

// Delete folder, check if sure
if (isset($_POST[ 'select-folder' ]) && ! isset($_POST[ 'delete-submit-sure' ])) {
    echo '<div class="alert alert-danger"><i class="fa-solid fa-trash-o fa-lg"></i> Are you sure you want to delete these folders?</div>';
    echo '<form method="post">';
    if (is_array($_POST[ 'select-folder' ])) {
        // echo debug($_POST['select-folder']);
        foreach ($_POST[ 'select-folder' ] as $folder => $status) : ?>
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="delete-folder[<?php echo $folder; ?>]" checked="">
                    <?php echo $folder; ?>
                </label>
            </div>
            <?php
        endforeach;
    }
    echo '<input type="submit" name="delete-folder-submit" value="Delete!" class="btn btn-primary">';
    echo '</form><br>';
    // If sure, delete folder
} elseif (isset($_POST[ 'delete-folder-submit' ]) && ! empty($_POST[ 'delete-folder' ])) {
    if (is_array($_POST[ 'delete-folder' ])) {
        foreach ($_POST[ 'delete-folder' ] as $folder => $status) {
            $filename = $path . '/' . $folder;
            if (! is_dir($filename)) {
                mkdir($filename);
            }

            if (rmdir($filename)) {
                echo '<p>Folder <u>' . $folder . '</u> has succesfully been deleted</p>';
                $folderKey = array_search($folder, $files);
                unset($files[ $folderKey ]);
                $_POST = array ();
            }
        }
    }
}


foreach ($files as $folderKey => $folderName) {
    $postFolderName = strtolower($folderName);
    $postFolderName = str_replace(' ', '-', $postFolderName);
    $filename       = $path . '/' . $folderName;

    // Edit folder name
    if (! empty($_POST[ 'rename' ][ $folderName ])) {
        echo '<p>True</p>';

        // $newName = $_POST['rename'][$folderName];
        // $newName = strtolower($newName);
        // $newName = str_replace(' ', '-', $newName);

        // if ( file_exists( $filename ) ) {
        //     $newFolderName = rename($filename, $newName);

        //     if ( $newFolderName === true ) {
        //         echo '<p>' . $folderName.' heeft de nieuwe naam "' . $newName . '" gekregen</p>';
        //         $_POST = array();
        //     }

        // }
    }
} // end foreach

echo '<form method="post">';
echo '<table class="table table-hover"><thead>';
// Titles
echo '<tr>';
echo '<th>' . new \Component\Form\Field\InputCheckbox(['class' => 'toggle']) . '</th>';
echo '<th>Project name</th>';
// echo '<th>Rename</th>';
echo '<th>Last modified</th>';
echo '<th></th>';
echo '</tr></thead><tbody>';

foreach ($files as $projectKey => $projectName) {
    // Item info
    $folderName = strtolower($projectName);
    $folderName = str_replace(' ', '-', $folderName);

    echo '<tr>';
    echo '<td>' . new \Component\Form\Field\InputCheckbox(['name' => 'project', 'value' => $projectKey]) . '</td>';
    echo '<td><a class="text-dark" href="/' . $projectName . '" target="_blank">' . $projectName . '</a></td>';
    // echo '<td><input type="text" value="" name="'.$folderName.'[change-folder]" class="form-control" placeholder="Enter a new project name"></td>';
    echo '<td>';

    $folder         = $path . '/' . $projectName;
    $folderModified = array ();
    if (file_exists($folder)) {
        $folderModified[ 'date' ]       = date("d/m/Y", filemtime($folder));
        $folderModified[ 'date-start' ] = date("Y-m-d", filemtime($folder));
        $folderModified[ 'time' ]       = date("H:i:s", filemtime($folder));


        $startTimeStamp = strtotime($folderModified[ 'date-start' ]);
        $endTimeStamp   = strtotime(date("Y-m-d"));

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $numberDays = intval($numberDays);

        echo sprintf(
            "<span class=\"modified-date\">%s</span><span class=\"modified-time\">om %s</span><span class=\"ago\">(%s days ago)</span>",
            $folderModified[ 'date' ],
            $folderModified[ 'time' ],
            $numberDays
        );
    }

    echo '</td>'; ?>
    <td class="text-end">
        <select name="action" class="form-select form-select-sm">
            <option value="">Selecteer actie</option>
            <option value="rename">Rename</option>
            <option value="delete">Delete</option>
            <option value="move">Move</option>
            <option value="run-command">Run Command</option>
            <option value="run-test">Run Test</option>
            <optgroup label="Doctrine ORM">
                <option value="make-migration">Make Migration</option>
                <option value="run-migration">Run Migration</option>
            </optgroup>
        </select>
    </td>


    <?php echo '</tr>';
} // end foreach

echo '</tbody></table>';

echo '</form>';