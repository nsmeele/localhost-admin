<?php

namespace Controller;

use Attribute\MenuLabel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    #[MenuLabel('Dashboard', icon: 'dashboard')]
    public function index(): Response
    {
        return $this->renderWithLayout('Hello world!!');
    }
}
