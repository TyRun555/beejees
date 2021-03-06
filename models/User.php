<?php

namespace models;

use core\App;
use core\BaseModel;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Сущность представляющая пользователя
 * @Entity
 * @Table(name="users")
 */
class User extends BaseModel
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private ?int $id;

    /** @Column(type="string", nullable=false) */
    private ?string $username = null;

    /** @Column(type="string", nullable=false) */
    private ?string $password = null;

    /** @Column(type="string", nullable=true) */
    private ?string $auth_key = null;

    public array $errors = [];
    public ?string $passwordString = null;

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setAuthKey(?string $auth_key): void
    {
        $this->auth_key = $auth_key;
    }

    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function login(): bool
    {
        $instance = App::$app->entityManager
            ->createQuery("SELECT u FROM models\User u WHERE u.username = :username")
            ->setParameter('username', $this->username)
            ->getOneOrNullResult();
        if ($instance instanceof User && password_verify($this->passwordString, $instance->password)) {
            $this->errors = [];
            $instance->auth_key = md5(time() . App::$app->getParam('cookieSalt'));
            setcookie('auth_key', $instance->auth_key, time() + 3600, '/'); //выдаем токен на час
            App::$app->entityManager->persist($instance);
            App::$app->entityManager->flush();
            return true;
        }
        $this->errors[] = 'Неверное имя пользователя или пароль!';
        return false;
    }

    /**
     * Логиним пользователя по токену
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public static function authorizeByKey(): ?User
    {
        $authKey = self::getAuthKeyCookie();
        if (!empty($authKey)) {
            $instance = App::$app->entityManager
                ->createQuery("SELECT u FROM models\User u WHERE u.auth_key = :auth_key")
                ->setParameter('auth_key', $authKey)
                ->getOneOrNullResult();
            if ($instance instanceof User) {
                return $instance;
            }
        }
        return null;
    }

    private static function getAuthKeyCookie()
    {
        return $_COOKIE['auth_key'] ?? null;
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function logout()
    {
        setcookie('auth_key', null, -1, '/');
        $this->setAuthKey(null);
        App::$app->entityManager->persist($this);
        App::$app->entityManager->flush();
    }

    public function renderErrors()
    {
        echo implode('<br>', $this->errors);
    }

}