<?php

use Module\Project\Resolver;

global $request, $formFactory;

$projectService = new \Service\ProjectService();

$formError = null;

$form = $formFactory->create(\Form\ProjectCreateForm::class);
$form->handleRequest($request);
$formRenderer = new \Component\FormRenderer($form);

if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();

    try {
        $projectTypeHandler = Resolver::fromType($data[ 'type' ]);
        $targetPath = $projectService->getProjectPath();
        if (!empty($data['parent'])) {
            $targetPath .= '/' . $data['parent'];
        }

        if (!is_dir($targetPath)) {
            throw new \RuntimeException('Target directory does not exist: ' . $targetPath);
        }

        $projectTypeHandler->handle($data[ 'name' ], $targetPath);
        header('Location: /projects');
        exit;
    } catch (\Throwable $e) {
        $formError = 'Error creating project: ' . $e->getMessage();
    }
} elseif ($form->isSubmitted()) {
    $formError = 'Please correct the errors in the form.';
}

?>

<div class="flex">
    <div class="w-full max-w-[50%]">
        <?php
        if ($formError) {
            echo '<div class="bg-red-300 font-medium mb-3 py-2 px-3 shadow-sm text-red-800 rounded-lg">' . $formError . '</div>';
        }
        echo $formRenderer;
        ?>
    </div>
</div>