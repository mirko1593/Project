<?php

namespace Tests\Unit;

use Hash;
use Tests\TestCase;
use App\{User, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_act_as_admin()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();

        $user->actAs(Role::admin());

        $this->assertContains(Role::admin()->name(), $user->roles->pluck('name'));
    }

    /** @test */
    function a_user_can_be_checked_as_admin_or_not()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $user->actAs(Role::admin());

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user2->isAdmin());
    }

    /** @test */
    function a_new_created_user_has_a_default_passport_same_to_phone_number()
    {
        $user = factory(User::class)->create();

        $this->assertTrue(Hash::check($user->phone, $user->password));
    }

    /** @test */
    function a_user_act_as_representative_cannot_be_created_without_email()
    {
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of Corporation']);
        $role = Role::representative();
        $user = factory(User::class)->create();
        $user->actAs($role);

        $this->assertDatabaseMissing('role_user', ['user_id' => $user->id, 'role_id' => $role->id]);
    }
}
