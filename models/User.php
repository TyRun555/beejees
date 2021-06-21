<?php
namespace models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string", nullable=false) */
    private $username;

    /** @Column(type="string", nullable=false) */
    private $password;

    /** @Column(type="string", nullable=true) */
    private $auth_key;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setPassword(string $password) : void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    public function setAuthKey(string $auth_key) : void
    {
        $this->auth_key = $auth_key;
    }

    public function getAuthKey() : ?string
    {
        return $this->auth_key;
    }


}