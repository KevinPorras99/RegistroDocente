<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitializeDatabase extends Command
{
    protected $signature = 'initialize:database';
    protected $description = 'Inicializa las tablas necesarias para la aplicación';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Ejecutando migraciones...');
        Artisan::call('migrate');

        $this->info('Ejecutando seeders...');
        Artisan::call('db:seed');

        $this->info('Base de datos inicializada correctamente.');
    }
}