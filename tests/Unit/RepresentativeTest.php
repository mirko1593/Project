<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\{User, Representative, Role};
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepresentativeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_create_a_representative_from_an_user()
    {
        factory(Role::class)->create(['name' => 'representative']);
        $user = factory(User::class)->create(['name' => 'john']);

        $representative = Representative::create($user);

        $this->assertContains('representative', $representative->roles->pluck('name'));
    }


    /** @test */
    function can_get_all_representatives()
    {
        $role = factory(Role::class)->create(['name' => 'representative', 'label' => 'representative of corporation']);
        factory(User::class, 3)->create()->each->actAs($role);
        factory(User::class, 2)->create();

        $representatives = Representative::all();

        $this->assertCount(3, $representatives);
    }
}
