<?php

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;

const CLI = true;
$_SERVER['REQUEST_URI'] = '/site/index';
include 'public/index.php';

$entityManager = $app->entityManager;
$config = new PhpFile(__DIR__.'/migrations.php');
return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
