<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;
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

    /** @test */
    public function it_should_be_able_to_authorize_access_to_a_route_based_on_the_permission(){

        Route::get('test-something-weird', function() {
            return 'test';
        })->middleware('permission:edit-articles');

        /** @var User $user */
        $user = User::factory()->createOne();

        $this->actingAs($user)->get('test-something-weird')
            ->assertForbidden();

        $user->givePermissionTo('edit-articles');
        
        $this->actingAs($user)
            ->get('test-something-weird')
            ->assertSuccessful();
    }

    /** @test */
    public function it_should_be_able_to_use_policies_with_my_permissions()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        $register = $user->employee()->first()->registers()->create([
            'type' => 'ENTRADA',
            'date_hour' => now(),
        ]);

        $employee2 = Employee::factory()->createOne();
        $this->actingAs($employee2->user)->delete(route('registers.destroy', $register))
            ->assertForbidden();
    }
}
