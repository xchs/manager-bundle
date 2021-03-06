<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

use Contao\ManagerBundle\ContaoManager\Plugin;
use Contao\ManagerBundle\HttpKernel\ContaoCache;
use Contao\ManagerBundle\HttpKernel\ContaoKernel;
use Symfony\Component\HttpFoundation\Request;

// Suppress error messages (see #1422)
@ini_set('display_errors', '0');

/** @var Composer\Autoload\ClassLoader */
$loader = require __DIR__.'/../vendor/autoload.php';

Plugin::autoloadModules(__DIR__.'/../system/modules');
ContaoKernel::setProjectDir(\dirname(__DIR__));

$kernel = new ContaoKernel('prod', false);

// Enable the Symfony reverse proxy
$kernel = new ContaoCache($kernel);
Request::enableHttpMethodParameterOverride();

// Handle the request
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
