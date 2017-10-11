<?php

namespace Tests\Feature;

use Auth;
use Tests\TestCase;
use App\{User, Role, Representative};
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageRepresentatives extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    function admin_can_view_all_representatives()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of a corporation']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());
        Representative::create(factory(User::class)->create(['name' => 'john', 'email' => 'john@example.com']));

        Auth::login($user);
        $response = $this->get('/representatives');

        $response->assertStatus(200);
        $response->assertSee('john');
    }

    /** @test */
    function unauthenticated_cannot_view_all_representatives()
    {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of a corporation']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        $response = $this->get('/representatives');
    }

    /** @test */
    function unauthorized_user_cannot_view_all_representatives()
    {
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        $user = factory(User::class)->create();
        Auth::login($user);

        $response = $this->get('/representatives');
    }

    /** @test */
    function admin_can_create_a_new_representative()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of a corporation']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        Auth::login($user);
        $userData = factory(User::class)->raw();
        $response = $this->post('/representatives', array_merge($userData, ['email' => 'foo@bar.com']));

        $response->assertStatus(302);
        $response->assertRedirect('/representatives');
    }

    /** @test */
    function admin_cannot_create_a_new_representative_without_an_email()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of a corporation']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        Auth::login($user);
        $userData = factory(User::class)->raw();
        $response = $this->post('/representatives', $userData);
    }

    /** @test */
    function admin_can_delete_a_representative()
    {
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of corporation']);
        $user1 = factory(User::class)->create(['email' => 'john@example.com']);
        $user1->actAs(Role::representative());

        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();
        $user->actAs(Role::admin());

        Auth::login($user);
        $response = $this->get('/representatives');

        $response->assertSee($user1->name);
    }
}
