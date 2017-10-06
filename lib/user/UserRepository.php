<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 06/10/2017
 * Time: 08:09
 */

class UserRepository
{
    public function add(User $user)
    {
        $params = [
          $user->getEmail(),
          $user->getPassword(),
          $user->isEnable(),
        ];
        $con = DbConnection::createConnection();
        $stm = $con->prepare('INSERT INTO `user`(email, password, enable) VALUES (?, ?, ?)');
        $stm->execute($params);
        $id = $con->lastInsertId();
        $con->exec("INSERT INTO `setting`(`user_id`) VALUES($id)");
        $user->setId($id);
        return $user;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|User
     */
    public function get($email, $password)
    {
        $con = DbConnection::createConnection();
        $stm = $con->prepare('SELECT id, email, password, enable FROM `user` WHERE email=? LIMIT 1');
        $stm->execute([$email]);
        /**@var $obj User */
        $obj = $stm->fetchObject(User::class);
        if ($obj === FALSE) {
            return FALSE;
        }

        if (password_verify($password, $obj->getPassword()) && $obj->isEnable()) {
            return $obj;
        }

        return FALSE;
    }

    /**
     * @param int $userId
     * @return Setting
     */
    public function getSetting($userId)
    {
        $con = DbConnection::createConnection();
        $res = $con->query('SELECT logo_path,parameters FROM setting WHERE user_id = ' . $userId);
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
        $con = DbConnection::createConnection();
        $stm = $con->prepare('UPDATE setting SET logo_path = ?, parameters = ? WHERE user_id = '.$setting->getIdUser());
        $stm->execute([
            $setting->getLogo(),
            json_encode($setting->getParameters())
        ]);
    }
}