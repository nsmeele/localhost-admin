<?php

namespace Controller;

use Component\Navigation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

abstract class AbstractController
{
    protected Navigation $routeNavigation;

    protected Request $request;

    protected UrlGenerator $urlGenerator;

    public function __construct()
    {
        global $routes, $request, $urlGenerator;
        $navigationExtractor   = new \Navigation\Extractor();
        $this->routeNavigation = new Navigation($navigationExtractor->extract($routes));
        $this->request         = $request;
        $this->urlGenerator    = $urlGenerator;
    }

    protected function renderWithLayout(
        string|callable $contentBlock,
        array $attr = array ()
    ): Response {
        ob_start();

        extract(array_merge([
            'navigation' => $this->routeNavigation,
            'title'      => $this->routeNavigation
                    ->getMenuItem($this->request->getRequestUri())
                    ?->getMenuLabel() ?? 'No title provided',
        ], $attr));

        require_once ROOT_PATH . '/templates/layout/header.php';

        if (is_callable($contentBlock)) {
            $contentBlock();
        } else {
            echo $contentBlock;
        }

        require_once ROOT_PATH . '/templates/layout/footer.php';

        $html = ob_get_clean();

        return new Response($html);
    }
}
