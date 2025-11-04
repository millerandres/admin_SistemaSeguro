<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\StorageConfig;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Configuración global
        StorageConfig::create([
            'config_key' => 'global_quota',
            'config_value' => (string)(10 * 1024 * 1024), // 10 MB
        ]);

        StorageConfig::create([
            'config_key' => 'forbidden_extensions',
            'config_value' => json_encode(['exe', 'bat', 'js', 'php', 'sh']),
        ]);
    }
}
