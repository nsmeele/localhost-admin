<div class="my-4">
    <button><i class="fa-solid fa-folder-open"></i>&nbsp;Init project</button>

</div>

<form action="" id="create-form">
    <div>
        <div>
            <div>
                <label for="project-name">Project name</label>
                <input type="text" id="project-name" name="project[name]">
            </div>

            <div>
                <label for="project-type">Project type</label>
                <select name="project[type]" id="project-type">
                    <option value="">- Select project type -</option>
                    <option value="symfony-7">Symfony 7 + ViteJS + TailwindCSS</option>
                    <option value="symfony-lts">Symfony LTS + ViteJS + TailwindCSS</option>
                    <option value="nextjs">NextJS + TailwindCSS</option>
                    <option value="reactjs">ReactJS + TailwindCSS</option>
                    <option value="drupal">Drupal</option>
                    <option value="wordpress">Wordpress</option>
                </select>
            </div>
        </div>
    </div>




</form>

<form action="" id="search-form">
    <div>
        <input type="text" placeholder="Search project" aria-label="Search project" aria-describedby="button-search">
        <button type="button" id="button-search"><i class="fa-solid fa-search"></i></button>
    </div>
</form>

<?php
$projectService = new \Service\ProjectService();
$projects       = $projectService->getProjects(excludeFiles: true);

if (! empty($projects)) {

    ?>

    <div class="grid grid-cols-3">

        <?php

        foreach ($projects as $project) {
            ?>

            <div class="col">
                <div class="border-top p-2 flex items-center">
                    <div>
                        <h6><i class="fa-solid fa-folder"></i><?php echo $project; ?></h6>
                    </div>
                    <select class="ms-auto form-select form-select-sm w-auto" name="" id="">
                        <option value="">Edit</option>
                        <option value="">Delete</option>

                        <optgroup label="Symfony">
                            <option value="">Run command</option>
                            <option value="">Create migration file</option>
                            <option value="">Run migration</option>
                        </optgroup>

                    </select>
                </div>

            </div>

            <?php
        }

        ?>
    </div>
    <?php
}



