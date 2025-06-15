<?php

namespace Controller;

use Attribute\MenuLabel;
use Component\FormRenderer;
use Form\ProjectCreateForm;
use Module\Project\Resolver;
use Service\ProjectService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects', name: 'projects')]
class ProjectController extends AbstractController
{

    private ProjectService $projectService;

    public function __construct()
    {
        parent::__construct();
        $this->projectService = new ProjectService();
    }

    #[Route('/', name: '_index')]
    #[MenuLabel('Projects', icon: 'folder')]
    public function index() : Response
    {
        return $this->renderWithLayout([$this, 'renderProjectsView']);
    }

    protected function renderProjectsView() : void
    {
        ?>
        <div class="my-4">
            <a href="/projects/new" class="button"><i class="fa-solid fa-folder-open"></i>&nbsp;New project</a>
        </div>

        <form action="" id="search-form">
            <div>
                <input type="text" placeholder="Search project" aria-label="Search project" aria-describedby="button-search">
                <button type="button" id="button-search"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>

        <?php
        $projects = $this->projectService->listProjects(excludeFiles: true);

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
    }

    #[Route('/new', name: '_new')]
    #[MenuLabel('New project')]
    public function new() : Response
    {
        global $request, $formFactory;
        $formError = null;

        $form = $formFactory->create(ProjectCreateForm::class);
        $form->handleRequest($request);
        $formRenderer = new FormRenderer($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $projectTypeHandler = Resolver::fromType($data[ 'type' ]);
                $targetPath         = $this->projectService->getProjectPath();

                if (! empty($data[ 'parent' ])) {
                    $targetPath .= '/'.$data[ 'parent' ];
                }

                if (! is_dir($targetPath)) {
                    throw new \RuntimeException('Target directory does not exist: '.$targetPath);
                }

                $projectTypeHandler->handle($data[ 'name' ], $targetPath);

                return new RedirectResponse('/projects/');
            } catch (\Throwable $e) {
                $formError = 'Error creating project: '.$e->getMessage();
            }
        } elseif ($form->isSubmitted()) {
            $formError = 'Please correct the errors in the form.';
        }

        $html = '<div class="flex">
            <div class="w-full max-w-[50%]">';

        if ($formError) {
            $html .= '<div class="bg-red-300 font-medium mb-3 py-2 px-3 shadow-sm text-red-800 rounded-lg">'
                .htmlspecialchars($formError).
                '</div>';
        }

        $html .= $formRenderer;
        $html .= '</div></div>';

        return $this->renderWithLayout($html);
    }

    #[Route('/{id}/edit', name: '_edit')]
    public function edit(string $id) : Response
    {
        // Logic to edit a project by ID
        return $this->renderWithLayout("Edit project ID: $id");
    }

    #[Route('/{id}/remove', name: '_remove')]
    public function remove(string $id) : Response
    {
        global $request, $urlGenerator;

        $path = (string)$request->query->get('path');

        $fileSystem = new \Symfony\Component\Filesystem\Filesystem();
        if ($fileSystem->exists($path)) {
            $fileSystem->remove($path);
        }

        return new RedirectResponse('/projects/');
    }

    #[Route('/{id}', name: '_show')]
    public function show(string $id) : Response
    {
        // Logic to show a project by ID
        return $this->renderWithLayout("Project ID: $id");
    }

}
