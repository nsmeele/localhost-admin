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
    public function index(): Response
    {
        return $this->renderWithLayout([$this, 'renderProjectsView']);
    }

    protected function renderProjectsView(): void
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
            <div class="grid md:grid-cols-2 xl:grid-cols-3 mt-8 gap-4">
                <?php
                foreach ($projects as $project) {
                    $projectNameParts = explode('/', $project->getRelativePathname());
                    $actionUrlParams  = array_filter([
                        'namespace' => trim($projectNameParts[ 0 ] ?? '', '/'),
                        'project'   => trim($projectNameParts[ 1 ] ?? '', '/')
                    ]);

                    $editUrl   = $this->urlGenerator->generate('projects_edit', $actionUrlParams);
                    $removeUrl = $this->urlGenerator->generate('projects_remove', $actionUrlParams);
                    ?>
                    <div class="p-4 flex items-center bg-gray-50 hover:bg-gray-200 rounded-lg">
                        <div>
                            <label for="<?php echo $project->getRelativePathname(); ?>-actions" class="font-medium text-lg">
                                <span class="mr-2"><i class="fa-solid fa-folder"></i></span>
                                <?php echo $project->getRelativePathname(); ?>
                            </label>
                        </div>
                        <div class="ml-auto">
                            <a href="<?php echo $editUrl; ?>"><i class="fa-solid fa-edit"></i></a>
                            <a href="<?php echo $removeUrl; ?>"><i class="fa-solid fa-trash-alt"></i></a>
                        </div>
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
    #[MenuLabel('New project', parent: 'projects_index')]
    public function new(): Response
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
                    $targetPath .= '/' . $data[ 'parent' ];
                }

                if (! is_dir($targetPath)) {
                    throw new \RuntimeException('Target directory does not exist: ' . $targetPath);
                }

                $projectTypeHandler->handle($data[ 'name' ], $targetPath);

                return new RedirectResponse($this->urlGenerator->generate('projects_index'));
            } catch (\Throwable $e) {
                $formError = 'Error creating project: ' . $e->getMessage();
            }
        } elseif ($form->isSubmitted()) {
            $formError = 'Please correct the errors in the form.';
        }

        $html = '<div class="flex">
            <div class="w-full max-w-[50%]">';

        if ($formError) {
            $html .= '<div class="bg-red-300 font-medium mb-3 py-2 px-3 shadow-sm text-red-800 rounded-lg">'
                . htmlspecialchars($formError) .
                '</div>';
        }

        $html .= $formRenderer;
        $html .= '</div></div>';

        return $this->renderWithLayout($html);
    }

    #[Route('/{namespace}/{project}/edit', name: '_edit')]
    #[Route('/{namespace}/edit', name: '_edit')]
    public function edit(string $namespace, ?string $project = null): Response
    {
        // Logic to edit a project by ID
        return $this->renderWithLayout("Edit project $namespace: $namespace", [
            'title' => 'Edit project: ' . $namespace . ($project ? '/' . $project : ''),
        ]);
    }

    #[Route('/{namespace}/{project}/remove', name: '_remove')]
    #[Route('/{namespace}/remove', name: '_remove')]
    public function remove(string $namespace, ?string $project = null): Response
    {
        $projectPath = $this->projectService->getProjectPath();
        $path        = sprintf("%s/%s", $projectPath, $namespace . ($project ? '/' . $project : ''));

        $fileSystem = new \Symfony\Component\Filesystem\Filesystem();
        if ($fileSystem->exists($path)) {
            $fileSystem->remove($path);
        }

        return new RedirectResponse($this->urlGenerator->generate('projects_index'));
    }

    #[Route('/{id}', name: '_show')]
    public function show(string $id): Response
    {
        // Logic to show a project by ID
        return $this->renderWithLayout("Project ID: $id");
    }
}
