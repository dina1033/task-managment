@csrf
<div class="mb-6">
    <label for="title" class="block text-lg font-semibold text-gray-700">Title</label>
    <input 
        type="text" 
        name="title" 
        id="title" 
        value="{{ old('title', $task->title ?? '') }}" 
        class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
        placeholder="Enter task title"
        required
    >
    @error('title')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<div class="mb-6">
    <label for="description" class="block text-lg font-semibold text-gray-700">Description</label>
    <textarea 
        name="description" 
        id="description" 
        class="w-full border border-gray-300 p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
        placeholder="Enter task description"
        rows="4"
        required
    >{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

<input type="hidden" name="is_completed" value="0">
<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

<div class="flex items-center space-x-4 mb-6">
    <input 
        type="checkbox" 
        name="is_completed" 
        id="is_completed" 
        class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        {{ old('is_completed', $task->is_completed ?? false) ? 'checked' : '' }}
        value="1"
    >
    <label for="is_completed" class="text-lg font-medium text-gray-800">Completed</label>
</div>

<button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
    {{ $buttonText }}
</button>
