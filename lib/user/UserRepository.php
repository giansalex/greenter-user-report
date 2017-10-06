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
        $stm = $con->prepare('INSERT INTO usuario(email, password, enable) VALUES (?, ?, ?)');
        $stm->execute($params);

        $id = $con->lastInsertId();
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
        $stm = $con->prepare('SELECT id, email, password, enable FROM usuario WHERE email=? LIMIT 1');
        $stm->execute([$email]);
        $obj = $stm->fetchObject(User::class);
        if ($obj === FALSE) {
            return FALSE;
        }

        if (password_verify($password, $obj->password) && $obj->enable) {
            return $obj;
        }

        return FALSE;
    }
}