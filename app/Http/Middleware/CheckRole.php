<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()) {

            $data['in_home'] = true;
            $data['from_url'] = \Request::getRequestUri();

            return redirect('/')->with($data);
        }

        // Если забанен
        if(isset($request->user()->profile->is_blocked) and $request->user()->profile->is_blocked){
            \Auth::logout();
            return redirect('/')->with('in_home', true);
        }

        if($request->user()->profile?->id == 2203){
            config(['debugbar.inject' => true]);
        }

        return $next($request);
    }
}
