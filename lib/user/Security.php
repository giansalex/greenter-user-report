<?php

/**
 * Class Security
 */
class Security
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
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        session_start();
        $this->repository = $repository;
        $this->tryGetUser();
    }

    /**
     * @param $email
     * @param $password
     */
    public function register($email, $password)
    {
        $user = new User();
        $user->setEmail($email)
            ->setPlainPassword($password);

        $this->repository->add($user);
        $this->saveSession($user);
        $this->user = $user;
    }

    public function login($email, $password)
    {
        $user = $this->repository->get($email, $password);
        if ($user === FALSE) {
            return false;
        }
        $this->saveSession($user);
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function isLoggin()
    {
        return !empty($this->user);
    }

    public function logout()
    {
        unset($_SESSION['u_id']);
        unset($_SESSION['u_email']);
        session_destroy();
    }

    private function tryGetUser()
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['u_email'])) {
            $this->user = (new User())
                ->setId(intval($_SESSION['u_id']))
                ->setEmail($_SESSION['u_email']);
        }
    }

    private function saveSession(User $user)
    {
        $_SESSION['u_id'] = $user->getId();
        $_SESSION['u_email'] = $user->getEmail();
    }
}