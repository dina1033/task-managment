<?php

namespace Tests\Unit;

use App\Http\Controllers\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private TaskController $controller;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new TaskController();
        $this->user = User::factory()->create(['type' => 'user']);
        $this->actingAs($this->user);
    }

    public function test_index_returns_view_with_tasks()
    {
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('tasks', $response->getData());
        $this->assertCount(5, $response->getData()['tasks']);
    }

    public function test_create_returns_view()
    {
        $response = $this->controller->create();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('tasks.create', $response->getName());
    }

    public function test_store_creates_task_and_redirects()
    {
        $request = new StoreTaskRequest([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'is_completed' => false
        ]);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id
        ]);
    }

    public function test_edit_returns_view_with_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->controller->edit($task);

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('task', $response->getData());
        $this->assertEquals($task->id, $response->getData()['task']->id);
    }

    public function test_update_modifies_task_and_redirects()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $request = new UpdateTaskRequest([
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ]);

        $response = $this->controller->update($request, $task);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title'
        ]);
    }

    public function test_destroy_deletes_task_and_redirects()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->controller->destroy($task);
    
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());

        $this->assertNotNull($task->fresh()->deleted_at);
    }

    public function test_toggle_changes_completion_status()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false
        ]);
    
        $response = $this->controller->toggle($task);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(
            ['completed' => true],
            $response->getData(true)
        );
        $status = $task->fresh()->is_completed == 1 ? true : false ;
        // Assert database update
        $this->assertTrue($status);
    }

    protected function mockAuthorize($task)
    {
        $this->controller->shouldReceive('authorize')
            ->with('toggle', $task)
            ->once();
    }
}
