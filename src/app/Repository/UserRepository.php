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

class UserRepository extends DbConnection
{
    public function add(User $user)
    {
        $params = [
            $user->getEmail(),
            $user->getPassword(),
            $user->isEnable(),
        ];
        $con = $this->createConnection();
        $stm = $con->prepare('INSERT INTO `user`(email, password, enable) VALUES (?, ?, ?)');
        $stm->execute($params);
        $id = $con->lastInsertId();
        $con->exec("INSERT INTO `setting`(`user_id`) VALUES($id)");
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
        $con = $this->createConnection();
        $stm = $con->prepare('SELECT COUNT(id) FROM `user` WHERE email = ?');
        $stm->execute([$email]);
        $count = $stm->fetchColumn();

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
        $con = $this->createConnection();
        $stm = $con->prepare('SELECT id, email, password, enable FROM `user` WHERE email=?');
        $stm->execute([$email]);
        /** @var $obj User */
        $obj = $stm->fetchObject(User::class);
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
        $con = $this->createConnection();
        $res = $con->query('SELECT logo_path,parameters FROM setting WHERE user_id = '.$userId);
        $obj = $res->fetchObject();
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
        $con = $this->createConnection();
        $stm = $con->prepare('UPDATE setting SET logo_path = ?, parameters = ? WHERE user_id = '.$setting->getIdUser());
        $stm->execute([
            $setting->getLogo(),
            json_encode($setting->getParameters()),
        ]);
    }
}
