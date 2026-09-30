<?php

// Provider xác định quyền truy cập, policy và các quy tắc phân quyền của người dùng.

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Illuminate\Support\Facades\Auth::extend('admin-scoped-session', function ($app, $name, array $config) {
            $scope = $app['request']->attributes->get('admin_session_scope', 'unscoped');
            return $app['auth']->createSessionDriver($name.'_'.$scope, $config);
        });

        //
    }
}
