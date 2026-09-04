<?php

namespace Tests\Feature;

use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Helper membuat Unit
    |--------------------------------------------------------------------------
    */

    private function createUnit(bool $isAdmin = false): Unit
    {
        return Unit::create([
            'level' => 'Wira',
            'school_name' => $isAdmin
                ? 'TEST ADMIN SCHOOL'
                : 'TEST USER SCHOOL',

            'address' => 'Jl. Testing No. 1',
            'city' => 'Bandung',
            'postal_code' => '40111',

            'coach_name' => 'Coach Testing',
            'trainer_name' => 'Trainer Testing',
            'commander_name' => 'Commander Testing',

            'email' => $isAdmin
                ? 'admin.testing@sailhunt.test'
                : 'user.testing@sailhunt.test',

            'username' => $isAdmin
                ? 'admin_testing'
                : 'user_testing',

            'password' => Hash::make(
                'password'
            ),

            'status' => 'verified',

            'is_admin' => $isAdmin,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Guest tidak boleh masuk area admin
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_admin_area(): void
    {
        $response = $this->get(
            '/admin/scores'
        );

        $response->assertRedirect(
            route('login')
        );

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Unit biasa tidak boleh masuk area admin
    |--------------------------------------------------------------------------
    */

    public function test_regular_unit_cannot_access_admin_area(): void
    {
        $unit = $this->createUnit(
            false
        );

        $response = $this
            ->withSession([
                'unit_id' => $unit->id,
            ])
            ->get('/admin/scores');

        $response->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | Admin boleh masuk area admin
    |--------------------------------------------------------------------------
    */

    public function test_admin_unit_can_access_admin_area(): void
    {
        $admin = $this->createUnit(
            true
        );

        $response = $this
            ->withSession([
                'unit_id' => $admin->id,
            ])
            ->get('/admin/scores');

        $response->assertOk();
    }

    /*
    |--------------------------------------------------------------------------
    | Guest tidak boleh masuk dashboard unit
    |--------------------------------------------------------------------------
    */

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(
            '/dashboard'
        );

        $response->assertRedirect(
            route('login')
        );

        $response->assertSessionHas(
            'error',
            'Silakan login terlebih dahulu.'
        );
    }
}