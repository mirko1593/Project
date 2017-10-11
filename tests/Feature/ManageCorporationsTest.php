<?php

namespace Tests\Feature;

use Auth;
use Tests\TestCase;
use App\{User, Role, Permission, Corporation};
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCorporationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    function admin_can_view_corporations_list()
    {
        factory(Corporation::class)->create(['name' => 'Example Corporation']);
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        Auth::login($user);
        $response = $this->get('/corporations');

        $response->assertStatus(200);
        $response->assertSee('Example Corporation');
    }

    /** @test */
    function unauthenticated_user_cannot_view_corporations_list()
    {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

        factory(Corporation::class)->create(['name' => 'Example Corporation']);
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        $response = $this->get('/corporations');
    }

    /** @test */
    function unauthorized_user_cannot_view_corporation_list()
    {
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        factory(Corporation::class)->create(['name' => 'Example Corporation']);
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();
        Auth::login($user);

        $response = $this->get('/corporations');
    }
}
