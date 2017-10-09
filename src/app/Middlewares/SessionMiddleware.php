<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 19:34
 */

namespace Greenter\App\Middlewares;

use Greenter\App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

class SessionMiddleware
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct(Router $router, \Twig_Environment $environment) {

        $this->router = $router;
        $this->environment = $environment;
    }

    /**
     * Example middleware invokable class
     *
     * @param  Request   $request  PSR7 request
     * @param  Response  $response PSR7 response
     * @param  callable  $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $user = $this->tryGetUser();
        if ($user === FALSE) {
            return $response->withRedirect($this->router->pathFor('login'));
        }

        $request = $request->withAttribute('user', $user);
        $this->environment->addGlobal('user', $user);

        $response = $next($request, $response);
        return $response;
    }

    private function tryGetUser()
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['u_email'])) {
            /**@var $email string*/
            $email = $_SESSION['u_email'];
            $user = (new User())
                ->setId(intval($_SESSION['u_id']))
                ->setEmail($email);

            return $user;
        }

        return FALSE;
    }
}