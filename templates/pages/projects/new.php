<?php

use Module\Project\Resolver;

global $request, $formFactory;

$projectService = new \Service\ProjectService();

$formError = null;

$form = $formFactory->create(\Form\ProjectCreateForm::class);
$form->handleRequest($request);
$formRenderer = new \Component\FormRenderer($form->createView());

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();

    try {
        $projectTypeHandler = Resolver::fromType($data[ 'type' ]);
        $projectTypeHandler->handle($data[ 'name' ], $projectService->getProjectPath());
        header('Location: /projects');
        exit;
    } catch (\Exception $e) {
        $formError = 'Error creating project: ' . $e->getMessage();
    }
}

?>

<div class="flex">
    <div class="w-full max-w-[50%]">
        <?php
        if ($formError) {
            echo '<div class="bg-red-300 p-3 text-red-800 rounded-lg">' . $formError . '</div>';
        }
        echo $formRenderer;
        ?>
    </div>
</div>