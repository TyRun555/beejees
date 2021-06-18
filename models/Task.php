<?php
namespace Models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity
 * @Table(name="tasks")
 */
class Task
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /** @Column(type="string", nullable=false) */
    private $username;

    /**
     * @Column(type="string", nullable=false)
     * @Assert\Regex('/^[a-z0-9._%+-]+@[^!@#$%^&*();:,?\/\=+<>]{2,63}\.[a-z]{2,}$/i')
     */
    private $email;

    /**
     * @Column(type="text", nullable=true)
     */
    private $text;

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

    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    public function getEmail() : ?string
    {
        return $this->email;
    }

    public function setText(string $text) : void
    {
        $this->auth_key = htmlspecialchars(strip_tags($text));
    }

    public function getText() : ?string
    {
        return html_entity_decode($this->text);
    }


}