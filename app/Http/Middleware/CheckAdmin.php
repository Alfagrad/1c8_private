<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HomeController;
use Closure;

class CheckAdmin
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
        //Чтобы не был пустой
        if (!$request->user()){
            return redirect('/login');
        }

        if($request->user()->site_role != '444' and $request->user()->site_role != '777'){
            return redirect('/login');
        }

        return $next($request);
    }
}
