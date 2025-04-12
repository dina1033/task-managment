<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureUserIsUserMiddlewareFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_routes()
    {
        $routes = [
            'GET' => route('dashboard'),
            'GET' => route('tasks.create'),
            'POST' => route('tasks.store'),
            'GET' => route('tasks.edit', 1),
            'PUT' => route('tasks.update', 1),
            'DELETE' => route('tasks.destroy', 1),
            'POST' => route('tasks.toggle', 1),
        ];

        foreach ($routes as $method => $route) {
            $response = $this->call($method, $route);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_admin_cannot_access_protected_routes()
    {
        $admin = User::factory()->create(['type' => 'admin']);
        $this->actingAs($admin);

        $task = Task::factory()->create(['user_id' => $admin->id]);

        $routes = [
            'GET' => route('dashboard'),
            'GET' => route('tasks.create'),
            'POST' => route('tasks.store'),
            'GET' => route('tasks.edit', $task),
            'PUT' => route('tasks.update', $task),
            'DELETE' => route('tasks.destroy', $task),
            'POST' => route('tasks.toggle', $task),
        ];

        foreach ($routes as $method => $route) {
            $response = $this->call($method, $route);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_regular_user_can_access_protected_routes()
    {
        $user = User::factory()->create(['type' => 'user']);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $routes = [
            'GET' => route('dashboard'),
            'GET' => route('tasks.create'),
            'POST' => route('tasks.store'),
            'GET' => route('tasks.edit', $task),
            'PUT' => route('tasks.update', $task),
            'DELETE' => route('tasks.destroy', $task),
            'POST' => route('tasks.toggle', $task),
        ];

        foreach ($routes as $method => $route) {
            $response = $this->call($method, $route);
            
            // For non-redirect responses, just verify status is not redirect
            if ($response->isRedirect()) {
                $this->assertNotEquals(route('login'), $response->getTargetUrl());
            } else {
                $response->assertSuccessful(); // 200-299 status
            }
        }
    }

    public function test_auth_routes_are_accessible_to_all()
    {
        $routes = [
            'GET' => route('login'),
            'GET' => route('register')
        ];
        foreach ($routes as $method => $route) {
            $response = $this->followingRedirects()->call($method, $route);
            $response->assertStatus(200); 
        }
    }
}