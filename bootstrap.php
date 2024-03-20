<?php

use App\Filters\ActiveFilter;
use App\Hydrator\OneColumnIntegerHydrator;
use App\Hydrator\OneColumnStringHydrator;
use App\Types\EnumBooleanType;
use App\Types\Listener\EnumTypeListener;
use App\Types\Specific\LocationTypePhp;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;

require_once 'vendor/autoload.php';

// Create a simple "default" Doctrine ORM configuration for Attribute
$isDevMode = true;
$proxyDir = null;
$cache = null;
$config = ORMSetup::createAttributeMetadataConfiguration(
    array(__DIR__ . '/src'),
    $isDevMode,
    $proxyDir,
    $cache,
);
$config->addFilter('active', ActiveFilter::class);

$connectionOptions = [
    'url' => 'mysql://root:root@demo-doctrine-mysql/demo-doctrine',
];

$entityManager = EntityManager::create($connectionOptions, $config);

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
