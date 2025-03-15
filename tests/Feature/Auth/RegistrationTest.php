<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Seed the tenants before each test
    $this->seed(\Database\Seeders\TenantTableSeeder::class);
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('new users can register', function () {
    $tenant = Tenant::first();

    $this->assertNotNull($tenant, 'No tenants found in the database.');

    $tenantDomain = $tenant->domain;

    $request = Request::create(
        '/register',
        'POST',
        [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ],
        [],
        [],
        ['HTTP_HOST' => $tenantDomain]
    );

    App::instance('request', $request);

    $response = $this->call('POST', '/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(302);

    $this->assertEquals($tenantDomain, request()->getHost(), 'The domain did not match the expected tenant domain.');

    $tenant = Tenant::where('domain', request()->getHost())->first();

    $this->assertNotNull($tenant, 'Tenant not found with the given domain.');

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user, 'User was not created.');

    $this->actingAs($user);

    $this->assertAuthenticated();

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertEquals($tenant->id, $user->tenant_id);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'tenant_id' => $tenant->id,
    ]);
});
