<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::create([
            'name' => 'Tenant 1',
            'domain' => 'tenant1.laravel.test',
            'database' => null,
        ]);

        Tenant::create([
            'name' => 'Tenant 2',
            'domain' => 'tenant2.laravel.test',
            'database' => null,
        ]);
    }
}
