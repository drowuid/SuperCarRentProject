<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Create application.
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // Definir chave de aplicação para testes se não estiver definida
        if (empty($app['config']['app.key'])) {
            $app['config']['app.key'] = 'base64:' . base64_encode(random_bytes(32));
        }

        // Criar banco de dados de teste antes de qualquer coisa
        $this->createTestDatabaseIfNotExists();

        return $app;
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Executando migrações automaticamente antes dos testes
        $this->artisan('migrate');
    }

    /**
     * Create test database if it doesn't exist.
     */
    private function createTestDatabaseIfNotExists(): void
    {
        // Verificar se estamos em ambiente de teste e usando MySQL
        if (app()->environment('testing') && config('database.default') === 'mysql') {
            $config = config('database.connections.mysql');

            try {
                // Conectar sem especificar o banco
                $connection = new \PDO(
                    "mysql:host={$config['host']};port={$config['port']}",
                    $config['username'],
                    $config['password'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );

                // Criar banco se não existir
                $connection->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (\PDOException $e) {
                // Se falhar, não quebrar os testes - o banco pode já existir
                // ou pode haver outro problema que será reportado pelo Laravel
            }
        }
    }
}
