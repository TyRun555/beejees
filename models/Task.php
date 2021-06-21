<?php

namespace models;

use core\App;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Tools\Pagination\Paginator;
use service\Pagination;
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

    public function setStatus(array $status): void
    {
        $this->status = json_encode($status) ?? null;
    }

    public function getStatus(): ?array
    {
        return json_decode($this->status , 1) ?: null;
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
        $statuses = $this->getStatus();
    }

    public static function getPagination(int $pageSize = 3)
    {
        $entityManager = App::$app->entityManager;
        $page = (int)App::$app->get('taskPage') ?: 1;
        $sortParam = substr(App::$app->get('tasksort'), 1) ?: 'id';
        $sortDirection = substr($sortParam, 0, 1) == '-' ? 'DESC' : 'ASC';

        $dql = "SELECT t FROM models\Task t ORDER BY t." . $sortParam . " " . $sortDirection;
        $query = $entityManager->createQuery($dql);
        $paginator = new Paginator($query);

        $totalTasks = count($paginator);

        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $pagination = new Pagination(3, $totalTasks, 'taskPage');
        return ['pagination' => $pagination, 'tasks' => $paginator];
    }


}