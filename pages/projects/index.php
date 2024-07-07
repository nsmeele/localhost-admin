<div class="my-4">
    <button class="btn btn-dark btn-sm"><i class="fa-solid fa-folder-open"></i>&nbsp;Init project</button>
    <button class="btn btn-dark btn-sm"><i class="fa-solid fa-file-alt"></i>&nbsp;Add file</button>

</div>


<?php
$projectService = new \Service\ProjectService();
$projects       = $projectService->getProjects(null, true);

if (! empty($projects)) {

    ?>

    <div class="row row-cols-lg-3">

        <?php

        foreach ($projects as $project) {
            ?>

            <div class="col">
                <div class="border-top p-2 d-flex align-items-center">
                    <div>
                        <h6 class="mb-0"><i class="fa-solid fa-folder me-2"></i><?php echo $project; ?></h6>
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



