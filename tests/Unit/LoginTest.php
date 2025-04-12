<?php

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LoginController();
    }

    /** @test */
    public function show_returns_login_view()
    {
        $response = $this->controller->show();
        
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('auth.login', $response->getName());
    }

    /** @test */
    public function login_redirects_user_on_successful_authentication()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'type' => 'user'
        ]);

        $request = new LoginRequest([
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(true);
        
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
    }

    /** @test */
    public function login_redirects_non_user_types_to_login_with_error()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'type' => 'admin'
        ]);

        $request = new LoginRequest([
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(true);
        
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($admin);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('login'), $response->getTargetUrl());
    }

    /** @test */
    public function login_returns_back_with_errors_on_failed_authentication()
    {
        $request = new LoginRequest([
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(false);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(session()->has('errors'));
    }
}