<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureUserIsUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnsureUserIsUserMiddlewareTest extends TestCase
{
    public function test_guest_is_redirected_to_login()
    {
        $request = Request::create('/dashboard', 'GET');
        $middleware = new EnsureUserIsUser();

        $response = $middleware->handle($request, function () {});

        $this->assertEquals(route('login'), $response->getTargetUrl());
    }

    public function test_admin_user_is_redirected_to_login()
    {
        $user = User::factory()->create(['type' => 'admin']);
        $this->actingAs($user);

        $request = Request::create('/dashboard', 'GET');
        $middleware = new EnsureUserIsUser();

        $response = $middleware->handle($request, function () {});

        $this->assertEquals(route('login'), $response->getTargetUrl());
    }

    public function test_regular_user_can_access()
    {
        $user = User::factory()->create(['type' => 'user']);
    
        $request = Request::create('/dashboard', 'GET');
        
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
    
        $middleware = new EnsureUserIsUser();
    
        $called = false;
        $response = $middleware->handle($request, function () use (&$called) {
            $called = true;
            return response('OK');
        });
    
        $this->assertTrue($called, 'Middleware did not allow regular user to access');
    }
}
