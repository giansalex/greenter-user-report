<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 19:28
 */

namespace Greenter\App\Controllers;

use Greenter\App\Services\UserService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

class SecurityController
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var UserService
     */
    private $service;
    /**
     * @var Router
     */
    private $router;

    public function __construct(
        Twig $view,
        UserService $service,
        Router $router) {

        $this->view = $view;
        $this->service = $service;
        $this->router = $router;
    }

    /**
     * @param Request    $request
     * @param Response   $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login($request, $response, $args)
    {
        $input = $request->getParsedBody();
        $invalid = false;
        if (isset($input['email']) &&
            isset($input['pass'])) {
            $result = $this->service->login($_POST['email'], $_POST['pass']);

            if ($result) {
                return $response->withRedirect($this->router->pathFor('index'));
            }
            $invalid = true;
        }
        return $this->view->render($response, 'security/login.html.twig', ['invalid' => $invalid]);
    }

    /**
     * @param Request    $request
     * @param Response   $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register($request, $response, $args)
    {
        $input = $request->getParsedBody();
        $invalid = false;
        if (isset($input['email']) &&
            isset($input['pass'])) {
            $result = $this->service->register($_POST['email'], $_POST['password']);

            if ($result) {
                return $response->withRedirect($this->router->pathFor('index'));
            }
            $invalid = true;
        }
        return $this->view->render($response, 'security/register.html.twig', ['invalid' => $invalid]);
    }

    /**
     * @param Request    $request
     * @param Response   $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout($request, $response, $args)
    {
        $this->service->logout();
        return $response->withRedirect($this->router->pathFor('login'));
    }
}