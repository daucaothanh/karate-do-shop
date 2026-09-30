<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AdminSessionScope
{
    public function handle(Request $request, Closure $next)
    {
        $scope = $request->route('admin_session');
        if ($scope === null) {
            return $next($request);
        }

        if ($scope === 'new') {
            abort_unless($request->isMethod('GET'), 419);
            $path = preg_replace('#^admin/session/new#', 'admin/session/'.bin2hex(random_bytes(16)), $request->path());
            return redirect('/'.$path.($request->getQueryString() ? '?'.$request->getQueryString() : ''));
        }

        abort_unless(preg_match('/\A[a-f0-9]{32}\z/', $scope), 404);
        $request->attributes->set('admin_session_scope', $scope);
        $previousScope = URL::getDefaultParameters()['admin_session'] ?? 'new';
        URL::defaults(['admin_session' => $scope]);
        // Keep the scope out of controller arguments and implicit model bindings.
        $request->route()->forgetParameter('admin_session');

        $original = config('session');
        $path = '/admin/session/'.$scope;
        config(['session.cookie' => 'admin_session_'.$scope, 'session.path' => $path]);
        app('cookie')->setDefaultPathAndDomain($path, $original['domain'], $original['secure'], $original['same_site']);

        try {
            $response = $next($request);
            $response->headers->set('Cache-Control', 'no-store, private');
            $response->headers->set('Referrer-Policy', 'same-origin');
            return $response;
        } finally {
            config(['session' => $original]);
            app('cookie')->setDefaultPathAndDomain($original['path'], $original['domain'], $original['secure'], $original['same_site']);
            URL::defaults(['admin_session' => $previousScope]);
        }
    }
}
