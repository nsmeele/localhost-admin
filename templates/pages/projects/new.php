<?php

use Module\Project\Resolver;

global $request;

$projectService = new \Service\ProjectService();

$formError = null;

if (!empty($request->get('create-form'))) {
    $projectType = \Module\Project\ProjectType::tryFrom($request->get('type', ''));
    $projectName = $request->get('name');
    if ($projectType && $projectName) {
        try {
            $projectTypeHandler = Resolver::fromType($projectType);
            $projectTypeHandler->handle($projectName, $projectService->getProjectPath());
            $formError = '<div class="bg-emerald-300 rounded p-3">Project \'' . $projectName . '\' created successfully!</div>';
        } catch (\Exception $e) {
            $formError = '<div class="bg-red-300 rounded p-3">Error creating project: ' . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $formError = '<div class="bg-red-300 rounded p-3">Please fill in all fields.</div>';
    }
}

?>

<div class="flex">
    <div class="w-full max-w-[50%]">
        <form action="" method="post">

            <?php
            if ($formError) {
                echo '<div class="mb-2">';
                echo $formError;
                echo '</div>';
            }
            ?>

            <div>
                <label for="project-name" class="mb-2">Project name</label>
                <input type="text" id="project-name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="name">
            </div>

            <div class="mt-2">
                <label for="project-type" class="mb-2">Project type</label>
                <select name="type" id="project-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">- Select project type -</option>
                    <?php
                    foreach (\Module\Project\ProjectType::getChoices() as $value => $label) {
                        echo "<option value=\"{$value}\">{$label}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mt-4">
                <button name="create-form" value="1" type="submit">Save</button>
            </div>

        </form>
    </div>
</div>