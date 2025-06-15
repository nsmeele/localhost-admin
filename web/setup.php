<?php

use Component\MenuComponent;
use Routing\AttributeRouteLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Validator\Validation;

define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

require_once ROOT_PATH.'/vendor/autoload.php';

global $request, $navigation, $currentNavigationItem, $formFactory;

$controllerPath = ROOT_PATH . '/app/Controller';
$loader = new AttributeDirectoryLoader(
    new FileLocator($controllerPath),
    new AttributeRouteLoader()
);
$routes = $loader->load($controllerPath);

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);

$matcher    = new UrlMatcher($routes, $context);
$attributes = $matcher->match($request->getPathInfo());
$request->attributes->add($attributes);

$kernel = new HttpKernel(
    new EventDispatcher(),
    new ControllerResolver(),
    null,
    new ArgumentResolver()
);

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

$validator   = Validation::createValidator();
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new HttpFoundationExtension())
    ->addExtension(new \Symfony\Component\Form\Extension\Validator\ValidatorExtension($validator))
    ->getFormFactory();

define("HOME_URL", $request->getSchemeAndHttpHost());

$basePath              = realpath(ROOT_PATH.'/templates/pages');
$menuService           = new \Service\MenuService($basePath);
$navigation            = new MenuComponent()->setItems($menuService->getItemsFromPath());
$currentNavigationItem = $navigation->getItemByUri($request->getRequestUri());
if (strlen($request->getRequestUri()) == 1) {
    $currentNavigationItem = $navigation->getItemByUri('/home.php');
}

$navigation->getItemByUri('/home.php')
    ?->setIcon('home')
    ->setTitle('Home');

$navigation->getItemByUri('/projects')?->setIcon('heart');
$navigation->getItemByUri('/projects/new.php')?->setTitle('New project');
$navigation->getItemByUri('/projects/edit.php')?->setTitle('Edit project');
$navigation->getItemByUri('/projects/remove.php')?->setTitle('Remove project');
