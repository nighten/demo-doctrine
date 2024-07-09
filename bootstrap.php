<?php

use App\Filters\ActiveFilter;
use App\Hydrator\OneColumnIntegerHydrator;
use App\Hydrator\OneColumnStringHydrator;
use App\Logger\DoctrineLogger;
use App\Logger\LoggerMiddleware;
use App\Types\EnumBooleanType;
use App\Types\Listener\EnumTypeListener;
use App\Types\Specific\LocationTypePhp;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;

require_once 'vendor/autoload.php';

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__ . '/src'),
    isDevMode: true,
);

$doctrineLogger = new DoctrineLogger();

$config->setMiddlewares([
    new LoggerMiddleware($doctrineLogger),
]);

$connection = DriverManager::getConnection([
    'dbname' => 'demo-doctrine',
    'user' => 'root',
    'password' => 'root',
    'host' => 'demo-doctrine-mysql',
    'driver' => 'pdo_mysql',
], $config);

$config->addFilter('active', ActiveFilter::class);

$entityManager = new EntityManager($connection, $config);

Type::addType(EnumBooleanType::ENUM_BOOLEAN, EnumBooleanType::class);
Type::addType('uuid', App\Types\UuidType::class);
Type::addType(LocationTypePhp::CODE, LocationTypePhp::class);

#$entityManager->getConnection()->executeQuery('SET NAMES utf8mb4');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(
    EnumBooleanType::ENUM_BOOLEAN,
    EnumBooleanType::ENUM_BOOLEAN
);
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(
    LocationTypePhp::CODE,
    LocationTypePhp::CODE
);
$entityManager->getConfiguration()->addCustomHydrationMode('OneColumnStringHydrator', OneColumnStringHydrator::class);
$entityManager->getConfiguration()->addCustomHydrationMode('OneColumnIntegerHydrator', OneColumnIntegerHydrator::class);

$entityManager->getEventManager()->addEventListener('postGenerateSchema', new EnumTypeListener());
