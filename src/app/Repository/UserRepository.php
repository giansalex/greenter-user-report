<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:01.
 */

namespace Greenter\App\Repository;

use Greenter\App\Models\Setting;
use Greenter\App\Models\User;
use Greenter\App\Services\PdoErrorLogger;
use Psr\Container\ContainerInterface;

class UserRepository
{
    /**
     * @var DbConnection
     */
    private $db;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UserRepository constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = $container->get(DbConnection::class);
    }

    public function add(User $user)
    {
        $params = [
            $user->getEmail(),
            $user->getPassword(),
            $user->isEnable(),
        ];
        $con = $this->db->getConnection();
        $stm = $con->prepare('INSERT INTO `user`(email, password, enable) VALUES (?, ?, ?)');
        $stm->execute($params);
        $this->handleError($stm);

        $id = $con->lastInsertId();
        $con->exec("INSERT INTO `setting`(`user_id`) VALUES($id)");
        $this->handleError($con);
        $user->setId($id);

        return $user;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function exist($email)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT COUNT(id) FROM `user` WHERE email = ?');
        $stm->execute([$email]);
        $count = $stm->fetchColumn();
        $this->handleError($stm);

        return $count > 0;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return bool|User
     */
    public function get($email, $password)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('SELECT id, email, password, enable FROM `user` WHERE email=?');
        $stm->execute([$email]);
        /** @var $obj User */
        $obj = $stm->fetchObject(User::class);
        $this->handleError($stm);
        if ($obj === false) {
            return false;
        }

        if (password_verify($password, $obj->getPassword()) && $obj->isEnable()) {
            return $obj;
        }

        return false;
    }

    /**
     * @param int $userId
     *
     * @return Setting
     */
    public function getSetting($userId)
    {
        $con = $this->db->getConnection();
        $res = $con->query('SELECT logo_path,parameters FROM setting WHERE user_id = '.$userId);
        $obj = $res->fetchObject();
        $this->handleError($con);
        $sett = new Setting();
        $sett->setIdUser($userId)
            ->setLogo($obj->logo_path)
            ->setParameters([]);
        if ($obj->parameters) {
            $sett->setParameters(json_decode($obj->parameters));
        }

        return $sett;
    }

    /**
     * @param Setting $setting
     */
    public function saveSetting(Setting $setting)
    {
        $con = $this->db->getConnection();
        $stm = $con->prepare('UPDATE setting SET logo_path = ?, parameters = ? WHERE user_id = '.$setting->getIdUser());
        $stm->execute([
            $setting->getLogo(),
            json_encode($setting->getParameters()),
        ]);
        $this->handleError($stm);
    }

    /**
     * @param \PDO|\PDOStatement $statement
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function handleError($statement)
    {
        if ($statement->errorCode() === '00000') {
            return;
        }

        $this->container
            ->get(PdoErrorLogger::class)
            ->err($statement);

        throw new \RuntimeException('cannot run sql query');
    }
}
