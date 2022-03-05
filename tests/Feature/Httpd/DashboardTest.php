<?php

namespace Tests\Feature\Httpd;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testOpenHomepageNotLoggedUser()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_an_action_that_requires_authentication()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/admin/medicals');
        $response->assertStatus(200);
    }

    public function test_an_action_that_require_set_default_password()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->get('/admin');
        $response->assertStatus(500);
        dd($response);
    }
}
