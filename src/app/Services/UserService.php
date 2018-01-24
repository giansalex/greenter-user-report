<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:04.
 */

namespace Greenter\App\Services;

use Greenter\App\Models\User;
use Greenter\App\Repository\UserRepository;
use RKA\Session;

class UserService
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var User
     */
    private $user;

    /**
     * Security constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register($email, $password)
    {
        if ($this->repository->exist($email)) {
            return false;
        }
        $user = new User();
        $user->setEmail($email)
            ->setPlainPassword($password)
            ->setEnable(true);

        $this->repository->add($user);
        $this->saveSession($user);
        $this->user = $user;

        return true;
    }

    public function login($email, $password)
    {
        $user = $this->repository->get($email, $password);
        if ($user === false) {
            return false;
        }
        $this->saveSession($user);
        $this->user = $user;

        return true;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function logout()
    {
        Session::destroy();
    }

    private function saveSession(User $user)
    {
        $session = new Session();
        $session->set('u_id', $user->getId());
        $session->set('u_email', $user->getEmail());
    }
}
