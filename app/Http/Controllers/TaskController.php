<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->paginate(9);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        Auth::user()->tasks()->create($request->only('title', 'description','is_completed'));

        return redirect()->route('dashboard')->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->only('title', 'description'));

        return redirect()->route('dashboard')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted.');
    }

    public function toggle(Task $task)
    {
        $this->authorize('toggle', $task);
        if($task->is_completed)
            $task->update(['is_completed' => false]);
        else
            $task->update(['is_completed' => true]);
        return response()->json(['completed' => $task->is_completed]);
    }
}

