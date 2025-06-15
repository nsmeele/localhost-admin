<?php

use Routing\AttributeRouteLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Validator\Validation;

define("ROOT_PATH", realpath(dirname(__FILE__, 2)));

require_once ROOT_PATH.'/vendor/autoload.php';

global $request, $formFactory, $routes, $urlGenerator;

$controllerPath = ROOT_PATH . '/app/Controller';
$loader = new AttributeDirectoryLoader(
    new FileLocator($controllerPath),
    new AttributeRouteLoader()
);
$routes = $loader->load($controllerPath);

$request = Request::createFromGlobals();

define("HOME_URL", $request->getSchemeAndHttpHost());

$context = new RequestContext();
$context->fromRequest($request);

$urlGenerator = new UrlGenerator($routes, $context);

$matcher    = new UrlMatcher($routes, $context);
$attributes = $matcher->match($request->getPathInfo());
$request->attributes->add($attributes);

$validator   = Validation::createValidator();
$formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new HttpFoundationExtension())
    ->addExtension(new \Symfony\Component\Form\Extension\Validator\ValidatorExtension($validator))
    ->getFormFactory();

$kernel = new HttpKernel(
    new EventDispatcher(),
    new ControllerResolver(),
    null,
    new ArgumentResolver()
);

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);