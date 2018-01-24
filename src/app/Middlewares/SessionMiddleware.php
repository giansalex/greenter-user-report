<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 19:34.
 */

namespace Greenter\App\Middlewares;

use Greenter\App\Models\User;
use Psr\Container\ContainerInterface;
use RKA\Session;
use Slim\Http\Request;
use Slim\Http\Response;

class SessionMiddleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Example middleware invokable class.
     *
     * @param Request  $request  PSR7 request
     * @param Response $response PSR7 response
     * @param callable $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke($request, $response, $next)
    {
        $user = $this->tryGetUser();
        if ($user === false) {
            /** @var $router \Slim\Router */
            $router = $this->container->get('router');

            return $response->withRedirect($router->pathFor('login'));
        }

        $request = $request->withAttribute('user', $user);
        /** @var $view \Slim\Views\Twig */
        $view = $this->container->get('view');
        $view->getEnvironment()->addGlobal('user', $user);

        $response = $next($request, $response);

        return $response;
    }

    private function tryGetUser()
    {
        $session = new Session();
        $id = $session->get('u_id');
        $email = $session->get('u_email');
        if ($id && $email) {
            /** @var $email string */
            $user = (new User())
                ->setId(intval($id))
                ->setEmail($email);

            return $user;
        }

        return false;
    }
}
