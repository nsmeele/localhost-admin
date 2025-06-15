<div class="my-4">
    <a href="/projects/new.php" class="button"><i class="fa-solid fa-folder-open"></i>&nbsp;New project</a>

</div>

<form action="" id="search-form">
    <div>
        <input type="text" placeholder="Search project" aria-label="Search project" aria-describedby="button-search">
        <button type="button" id="button-search"><i class="fa-solid fa-search"></i></button>
    </div>
</form>

<?php
$projectService = new \Service\ProjectService();
$fileSystem     = new \Symfony\Component\Filesystem\Filesystem();
$projects       = $projectService->listProjects(excludeFiles: true);

if ($projects->hasResults()) { ?>
    <div class="grid grid-cols-3">
        <?php
        foreach ($projects as $project) {
            ?>

            <div class="border-top p-2 flex items-center">
                <div>
                    <label for="<?php
                    echo $project->getRelativePathname(); ?>-actions" class="font-bold text-lg">
                        <i class="fa-solid fa-folder"></i><?php
                        echo $project->getRelativePathname(); ?></label>
                </div>
                <select class="ms-auto form-select form-select-sm w-auto" id="<?php
                echo $project->getRelativePathname(); ?>-actions">
                    <option value="">- Select option-</option>
                    <option value="edit">Edit</option>
                    <option value="delete">Delete</option>

                    <optgroup label="Symfony">
                        <option value="">Run command</option>
                        <option value="">Create migration file</option>
                        <option value="">Run migration</option>
                    </optgroup>

                </select>
            </div>


            <?php
        }
        ?>
    </div>
    <?php
} else {
    ?>
    <div>No projects found.</div>
    <?php
}



