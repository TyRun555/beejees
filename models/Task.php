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
 * Сущность представляющая задачу
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
        self::STATUS_EDITED => 'отредактировано администратором',
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
        return json_decode($this->status, 1) ?: null;
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
        $this->text = trim(htmlspecialchars($text));
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Возвращает отформатированный текст задачи
     * @return string
     */
    public function renderText()
    {
        return html_entity_decode(nl2br($this->getText()));
    }

    /**
     * Возвращает шаблон статуса в таблице
     * @return string|null
     */
    public function printableStatus()
    {
        $statuses = $this->getStatus();
        return is_array($statuses)
            ? implode('<br>', array_map(function ($status) {
                return '<span class="badge bg-success">' . self::STATUSES[$status] . '</span>';
            }, $statuses))
            : null;
    }

    /**
     * Возвращает массив с пагинацией и моделями для вывода на странице
     * @param int $pageSize
     * @return array
     */
    public static function getPagination(int $pageSize = 3): array
    {
        $entityManager = App::$app->entityManager;
        $page = (int)App::$app->get('taskPage') ?: 1;

        $sortParam = stristr(App::$app->get('sort'), '-')
            ? substr(App::$app->get('sort'), 1)
            : App::$app->get('sort');

        $sortDirection = substr(App::$app->get('sort'), 0, 1) == '-' ? 'DESC' : 'ASC';

        $sortDirection = $sortParam == 'status' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; //Для статуса обратная логика из-за типа колонки JSON

        $dql = "SELECT t FROM models\Task t ORDER BY t." . ($sortParam ?: 'id') . " " . $sortDirection;
        $query = $entityManager->createQuery($dql);
        $paginator = new Paginator($query);

        $totalTasks = count($paginator);

        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $pagination = new Pagination(3, $totalTasks, 'taskPage');
        return ['pagination' => $pagination, 'tasks' => $paginator];
    }

    public function isDone(): bool
    {
        $status = $this->getStatus();
        return is_array($status) && in_array(self::STATUS_DONE, $status);
    }


}