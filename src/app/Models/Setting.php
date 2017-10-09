<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 19:56
 */

namespace Greenter\App\Models;


class Setting
{
    /**
     * @var integer
     */
    private $idUser;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     * @return Setting
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Setting
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return Setting
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }
}