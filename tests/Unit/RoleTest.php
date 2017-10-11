<?php

namespace Tests\Unit;

use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_an_super_admin_role()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);

        $role = Role::admin();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('admin', $role->name());
    }

    /** @test */
    function it_has_a_representative_role()
    {
        factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of corporation']);

        $role = Role::representative();

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('representative', $role->name());
    }

    /** @test */
    function there_only_single_instance_for_each_role()
    {
        factory(Role::class)->create(['name' => 'admin', 'label' => 'administrator']);

        $admin1 = Role::admin();
        $admin2 = Role::admin();

        $this->assertEquals($admin1->id, $admin2->id);
    }
}
