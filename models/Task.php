<?php

namespace models;

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
    const STATUS_NEW = null;
    const STATUS_EDITED = 1;
    const STATUS_DONE = 2;


    const STATUSES = [
        self::STATUS_NEW => '',
        self::STATUS_EDITED => 'Отредактировано',
        self::STATUS_DONE => 'Выполнено',
    ];

    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    private ?int $id;

    /** @Column(type="string", nullable=false) */
    private ?string $username;

    /**
     * @Column(type="string", nullable=false)
     * @Assert\Regex('/^[a-z0-9._%+-]+@[^!@#$%^&*();:,?\/\=+<>]{2,63}\.[a-z]{2,}$/i')
     */
    private ?string $email;

    /**
     * @Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @Column(type="json", nullable=true)
     */
    private ?string $status = null;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setStatus(int $status): void
    {
        $oldStatus = json_decode($this->status, 1);
        $this->status = json_encode(array_merge($oldStatus, [$status]));
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setText(string $text): void
    {
        $this->text = htmlspecialchars(strip_tags($text));
    }

    public function getText(): ?string
    {
        return html_entity_decode($this->text);
    }

    public function printableStatus()
    {
        if (is_array(self::STATUSES[$this->status])) {

        } else {
            return self::STATUSES[$this->status];
        }
    }


}