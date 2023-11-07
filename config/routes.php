<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use  `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
  /*
   * The default class to use for all routes
   *
   * The following route classes are supplied with CakePHP and are appropriate
   * to set as the default:
   *
   * - Route
   * - InflectedRoute
   * - DashedRoute
   *
   * If no call is made to `Router::defaultRouteClass()`, the class used is
   * `Route` (`Cake\Routing\Route\Route`)
   *
   * Note that `Route` does not do any inflections on URLs which will result in
   * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
   * `{action}` markers.
   */
  $routes->setRouteClass(DashedRoute::class);

  $routes->scope('/', function (RouteBuilder $builder): void {
    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $builder->connect('/pages/*', 'Pages::display');

    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/{controller}', ['action' => 'index']);
     * $builder->connect('/{controller}/{action}/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */

    // Rota para o controller Stores e ação index (listagem)
    $builder->connect('/stores', ['controller' => 'Stores', 'action' => 'index']);

    // Rota para ação view (visualização) em StoresController com um parâmetro {id}
    $builder->connect('/stores/view/:id', ['controller' => 'Stores', 'action' => 'view'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    // Rota para ação add (criação) em StoresController
    $builder->connect('/stores/add', ['controller' => 'Stores', 'action' => 'add']);

    // Rota para ação edit (edição) em StoresController com um parâmetro {id}
    $builder->connect('/stores/edit/:id', ['controller' => 'Stores', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    // Rota para ação delete (exclusão) em StoresController com um parâmetro {id}
    $builder->connect('/stores/delete/:id', ['controller' => 'Stores', 'action' => 'delete'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);

    // Rotas para o controller Addresses com ações semelhantes
    $builder->connect('/addresses', ['controller' => 'Addresses', 'action' => 'index']);
    $builder->connect('/addresses/view/:id', ['controller' => 'Addresses', 'action' => 'view'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    $builder->connect('/addresses/add', ['controller' => 'Addresses', 'action' => 'add']);
    $builder->connect('/addresses/edit/:id', ['controller' => 'Addresses', 'action' => 'edit'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);
    $builder->connect('/addresses/delete/:id', ['controller' => 'Addresses', 'action' => 'delete'])
      ->setPatterns(['id' => '\d+'])
      ->setPass(['id']);


    $builder->fallbacks();
  });

  /*
   * If you need a different set of middleware or none at all,
   * open new scope and define routes there.
   *
   * ```
   * $routes->scope('/api', function (RouteBuilder $builder): void {
   *     // No $builder->applyMiddleware() here.
   *
   *     // Parse specified extensions from URLs
   *     // $builder->setExtensions(['json', 'xml']);
   *
   *     // Connect API actions here.
   * });
   * ```
   */
};
