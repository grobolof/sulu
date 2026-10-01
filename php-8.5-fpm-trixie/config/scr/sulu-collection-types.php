<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Bundle\MediaBundle\DataFixtures\ORM\LoadCollectionTypes;
use Symfony\Component\Dotenv\Dotenv;

$projectDir = getenv('APP_PATH');
if (!is_string($projectDir) || $projectDir === '') {
    $projectDir = getcwd() ?: '';
}

if ($projectDir === '' || !is_file($projectDir.'/vendor/autoload.php')) {
    fwrite(STDERR, "APP_PATH does not point at a Sulu project\n");
    exit(1);
}

require $projectDir.'/vendor/autoload.php';

if (is_file($projectDir.'/.env') && class_exists(Dotenv::class)) {
    (new Dotenv())->bootEnv($projectDir.'/.env');
}

$env = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
if (!is_string($env) || $env === '') {
    $env = 'dev';
}

$debugRaw = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ('prod' !== $env);
$debug = is_bool($debugRaw) ? $debugRaw : filter_var($debugRaw, FILTER_VALIDATE_BOOLEAN);

$kernel = new Kernel($env, $debug, Kernel::CONTEXT_ADMIN);
$kernel->boot();

try {
    $doctrine = $kernel->getContainer()->get('doctrine');
    if (!$doctrine instanceof ManagerRegistry) {
        throw new RuntimeException('Doctrine is not available');
    }

    $entityManager = $doctrine->getManager();
    if (!$entityManager instanceof EntityManagerInterface) {
        throw new RuntimeException('Doctrine ORM is not available');
    }

    // system_collections создаёт коллекции с type id = 2.
    // Эти строки пишет LoadCollectionTypes; шаг fixtures целиком не вызываем,
    // чтобы не накатить фикстуры приложения.
    (new LoadCollectionTypes())->load($entityManager);

    $connection = $entityManager->getConnection();
    if ($connection->getDatabasePlatform() instanceof PostgreSQLPlatform) {
        $connection->executeQuery(
            "SELECT setval(pg_get_serial_sequence('me_collection_types', 'id'), (SELECT MAX(id) FROM me_collection_types))"
        );
    }
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage()."\n");
    $kernel->shutdown();
    exit(1);
}

$kernel->shutdown();
