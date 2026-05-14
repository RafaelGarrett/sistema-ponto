<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PermissionUserTest extends TestCase
{
    /** @test */
    public function it_checks_if_user_has_permission()
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $user->givePermissionTo('edit-articles');
        $this->assertTrue($user->hasPermissionTo('edit-articles'));
        $this->assertDatabaseHas('permissions', ['name' => 'edit-articles']);
    }
}
