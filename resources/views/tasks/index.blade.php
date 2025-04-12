@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">My Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ New Task</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($tasks as $task)
            <div class="task-card">
                <div class="grid grid-cols-1 gap-4 card p-4 rounded-lg cursor-pointer shadow-md bg-white h-50" >
                    <div class="card-body grid-cols-8">
                        <div class="flex justify-between" data-task-id="{{ $task->id }}"
                            onclick="toggleCompletion({{ $task->id }})">
                            <h5 class="text-xl font-semibold text-gray-800">{{ \Illuminate\Support\Str::limit($task->title, 20, '...') }}</h5>
                            <p class="text-sm mt-2 font-medium status-text {{ $task->is_completed ? 'text-green-600' : 'text-red-600' }}">
                                {{ $task->is_completed ? 'Completed' : 'Not Completed' }}
                            </p>
                        </div>  
                        <p class="text-gray-600 mt-2">
                            {{ \Illuminate\Support\Str::limit($task->description, 70, '...') }}
                        </p>

                    </div>
                    <div class="flex justify-end mt-auto">
                        <button 
                            onclick="openModal({{ $task->id }})" 
                            class="bg-blue-300 mr-2 text-white px-4 py-2 rounded"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('tasks.edit', $task) }}" class="bg-gray-500 mr-2 text-white px-4 py-2 rounded"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button class="bg-red-400 text-white px-4 py-2 rounded"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-6">
        {{ $tasks->links() }}
    </div>

    <div id="taskModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">
                <i class="fas fa-times"></i>
            </button>
            <h2 class="text-xl font-bold mb-2">Task Title</h2>
            <p class="text-gray-700 mb-5" id="modalTitle"></h2>
            <h2 class="text-xl font-bold mb-2" >Task Description</h2>
            <p class="text-gray-700" id="modalDescription">Task Description</p>
        </div>
    </div>
</div>

<script>
    function toggleCompletion(taskId) {
        fetch(`/tasks/${taskId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                task_id: taskId,
            })
        })
        .then(response => response.json())
        .then(data => {
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            const statusText = card.querySelector('.status-text');

            if (data.completed) {
                statusText.textContent = 'Completed';
                statusText.classList.remove('text-red-600');
                statusText.classList.add('text-green-600');
            } else {
                statusText.textContent = 'Not Completed';
                statusText.classList.remove('text-green-600');
                statusText.classList.add('text-red-600');
            }
        });
    }

    const tasks = @json($tasks->keyBy('id'));

    function openModal(taskId) {
        const task = tasks[taskId];
        document.getElementById('modalTitle').textContent = task.title;
        document.getElementById('modalDescription').textContent = task.description;
        document.getElementById('taskModal').classList.remove('hidden');
        document.getElementById('taskModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('taskModal').classList.remove('flex');
        document.getElementById('taskModal').classList.add('hidden');
    }
</script>
@endsection
