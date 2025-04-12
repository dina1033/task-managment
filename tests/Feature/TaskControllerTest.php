<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['type' => 'user']);
        $this->actingAs($this->user);
    }

    public function test_index_displays_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
        $this->assertCount(3, $response->viewData('tasks'));
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('tasks.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.create');
    }

    public function test_store_creates_new_task()
    {
        $data = [
            'title' => 'New Task',
            'description' => 'Task Description',
            'is_completed' => false,
            'user_id'=> $this->user->id
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id
        ]);
    }

    public function test_edit_displays_task_form()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('tasks.edit', $task));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.edit');
        $response->assertViewHas('task', $task);
    }

    public function test_update_modifies_existing_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        $response = $this->put(route('tasks.update', $task), $data);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_destroy_deletes_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Task deleted.');
        $this->assertNotNull($task->fresh()->deleted_at);
    }

    public function test_toggle_changes_completion_status()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false
        ]);

        $response = $this->post(route('tasks.toggle', $task));

        $response->assertStatus(200);
        $response->assertJson(['completed' => true]);
        $status = $task->fresh()->is_completed == 1 ? true : false ;
        $this->assertTrue($status);
    }

    public function test_user_cannot_access_other_users_tasks()
    {
        $otherUser = User::factory()->create(['type' => 'user']);
        $task = Task::factory()->create(['user_id' => $otherUser->id]);
    
        // Disable CSRF protection for these tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    
        // Test GET edit
        $response = $this->get(route('tasks.edit', $task));
        $response->assertForbidden();
    
        // Test PUT update
        $response = $this->put(route('tasks.update', $task), ['title' => 'Hacked']);
        $response->assertForbidden();
    
        // Test DELETE destroy
        $response = $this->delete(route('tasks.destroy', $task));
        $response->assertForbidden();
    
        // Test POST toggle
        $response = $this->post(route('tasks.toggle', $task));
        $response->assertForbidden();
    }
}