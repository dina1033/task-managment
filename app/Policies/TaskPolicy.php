<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserType;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
    }

    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
    }

    public function delete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
    }

    public function toggle(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
    }

    public function restore(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
    }
    
    public function forceDelete(User $user, Task $task)
    {
        return $user->id === $task->user_id || $user->type === UserType::ADMIN;
        }
}
