<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminSessionScopeTest extends TestCase
{
    private $browserCookies = [];

    protected function setUp(): void
    {
        parent::setUp();
        $handler = new ArraySessionHandler(120);
        $this->app['session']->extend('scope-test', fn () => $handler);
        config(['session.driver' => 'scope-test']);
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); $table->string('name');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email');
            $table->string('password'); $table->string('status');
            $table->unsignedBigInteger('role_id'); $table->rememberToken(); $table->timestamps();
        });
        (require database_path('migrations/tao_bang_ca_lam_va_cham_cong.php'))->up();
        DB::table('roles')->insert([['id' => 1, 'name' => 'admin'], ['id' => 2, 'name' => 'staff']]);
        foreach ([1, 2, 3] as $id) {
            User::create(['name' => 'User '.$id, 'email' => "user{$id}@example.test", 'password' => Hash::make('password123'), 'status' => 'active', 'role_id' => $id === 1 ? 1 : 2]);
        }
        Route::middleware(['web', 'staff'])->get('/admin/session/{admin_session}/test-identity', function () {
            return response()->json(['id' => Auth::guard('admin')->id(), 'csrf' => csrf_token(), 'link' => route('admin.users.edit', 2)]);
        });
        Route::middleware(['web', 'staff'])->get('/admin/session/{admin_session}/test-user/{user}', function (User $user) {
            return response()->json(['id' => $user->id]);
        });
    }

    private function visit($method, $url, array $data = [])
    {
        // Simulate independent PHP requests, retaining the browser's cookie jar.
        Auth::forgetGuards();
        Auth::shouldUse('web');
        $this->app['session']->forgetDrivers();
        $this->app->forgetInstance('session.store');
        foreach ($this->app['cookie']->getQueuedCookies() as $cookie) {
            $this->app['cookie']->unqueue($cookie->getName(), $cookie->getPath());
        }
        $path = parse_url($url, PHP_URL_PATH);
        $cookies = [];
        foreach ($this->browserCookies as $cookie) {
            if (str_starts_with($path, $cookie->getPath()) && (!$cookie->getExpiresTime() || $cookie->getExpiresTime() > time())) {
                $cookies[$cookie->getName()] = $cookie->getValue();
            }
        }
        $response = $this->call($method, $url, $data, $cookies);
        foreach ($response->headers->getCookies() as $cookie) {
            $this->browserCookies[$cookie->getName().'|'.$cookie->getPath()] = $cookie;
        }
        return $response;
    }

    public function test_three_accounts_stay_independent_when_one_logs_out()
    {
        $scopes = [];
        foreach ([1, 2, 3] as $id) {
            $login = $this->visit('GET', '/admin/login')->headers->get('Location');
            $scopes[$id] = substr(parse_url($login, PHP_URL_PATH), 0, -strlen('/login'));
            $this->visit('GET', $login)->assertOk();
            $this->visit('POST', $login, ['email' => "user{$id}@example.test", 'password' => 'password123', 'remember' => 'on'])->assertRedirect(url($scopes[$id]));
        }
        $tokens = [];
        foreach ($scopes as $id => $path) {
            $response = $this->visit('GET', $path.'/test-identity')->assertOk()->assertJson(['id' => $id]);
            $tokens[] = $response->json('csrf');
            $this->assertSame(url($path.'/users/2/edit'), $response->json('link'));
        }
        $this->assertCount(3, array_unique($tokens));
        $this->visit('GET', $scopes[1].'/test-user/2')->assertOk()->assertJson(['id' => 2]);
        $this->visit('POST', $scopes[2].'/logout')->assertRedirect(url($scopes[2].'/login'));
        $this->visit('GET', $scopes[2].'/test-identity')->assertRedirect(url($scopes[2].'/login'));
        $this->visit('GET', $scopes[1].'/test-identity')->assertJson(['id' => 1]);
        $this->visit('GET', $scopes[3].'/test-identity')->assertJson(['id' => 3]);
    }

    public function test_scope_url_alone_does_not_authenticate_and_remember_cookie_is_scoped()
    {
        $path = '/admin/session/'.str_repeat('b', 32);
        $this->visit('GET', $path.'/login')->assertOk();
        $this->visit('POST', $path.'/login', ['email' => 'user1@example.test', 'password' => 'password123', 'remember' => 'on'])->assertRedirect();
        $remember = array_filter($this->browserCookies, fn ($cookie) => str_starts_with($cookie->getName(), 'remember_'));
        $this->assertCount(1, $remember);
        $this->assertSame($path, reset($remember)->getPath());
        // Lose the session cookie; remember-me must recover only this account.
        $this->browserCookies = $remember;
        $this->visit('GET', $path.'/test-identity')->assertJson(['id' => 1]);
        $this->visit('GET', '/admin/session/'.str_repeat('c', 32).'/test-identity')->assertRedirect();
        $this->browserCookies = [];
        $this->visit('GET', $path.'/test-identity')->assertRedirect(url($path.'/login'));
    }

    public function test_forms_reject_csrf_tokens_from_another_session()
    {
        $this->app->bind(\App\Http\Middleware\VerifyCsrfToken::class, function ($app) {
            return new class($app, $app['encrypter']) extends \App\Http\Middleware\VerifyCsrfToken {
                protected function runningUnitTests() { return false; }
            };
        });
        $first = '/admin/session/'.str_repeat('d', 32).'/login';
        $second = '/admin/session/'.str_repeat('e', 32).'/login';
        preg_match('/name="_token" value="([^"]+)"/', $this->visit('GET', $first)->getContent(), $firstToken);
        preg_match('/name="_token" value="([^"]+)"/', $this->visit('GET', $second)->getContent(), $secondToken);
        $credentials = ['email' => 'user1@example.test', 'password' => 'password123'];
        $this->visit('POST', $second, $credentials + ['_token' => $firstToken[1]])->assertStatus(419);
        $this->visit('POST', $second, $credentials + ['_token' => $secondToken[1]])->assertRedirect(substr(url($second), 0, -6));
    }
}
