<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
           
            // dd("Chưa Đăng Nhập Nên Ko Vào Đc");
            return redirect()->route('admin.index')->withErrors([
                'loginError' => 'Bạn cần đăng nhập để truy cập trang này.',
            ]);
        }

       
        return $next($request);
    }
}
