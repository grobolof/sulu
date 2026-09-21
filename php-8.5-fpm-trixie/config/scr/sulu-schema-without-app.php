<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
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

    $classes = array_values(array_filter(
        $entityManager->getMetadataFactory()->getAllMetadata(),
        static fn (ClassMetadata $metadata): bool => !str_starts_with($metadata->getName(), 'App\\'),
    ));

    // Пустой фильтр оставляет в сравнении только таблицы из переданных классов,
    // поэтому таблицы App\ не создаются и не удаляются.
    $entityManager->getConnection()->getConfiguration()->setSchemaAssetsFilter(
        static fn (mixed $asset): bool => false,
    );

    $tool = new SchemaTool($entityManager);
    $tool->updateSchema($classes);
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage()."\n");
    $kernel->shutdown();
    exit(1);
}

$kernel->shutdown();
