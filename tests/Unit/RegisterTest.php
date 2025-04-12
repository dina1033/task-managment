<?php

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new RegisterController();
    }

    /** @test */
    public function create_returns_register_view()
    {
        $response = $this->controller->create();
        
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('auth.register', $response->getName());
    }

    /** @test */
    public function store_creates_user_and_logs_in()
    {
        $request = new RegisterRequest([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'type' => 'user'
        ]);
    }

    /** @test */
    public function store_hashes_password_properly()
    {
        $request = new RegisterRequest([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
    
        $this->controller->store($request);
    
        $user = User::first();
        $this->assertNotEquals('password', $user->password);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}