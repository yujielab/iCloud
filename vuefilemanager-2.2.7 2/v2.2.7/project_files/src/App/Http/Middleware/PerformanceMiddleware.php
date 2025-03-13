<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PerformanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 设置更好的响应头
        $response = $next($request);
        
        if (method_exists($response, 'header')) {
            // 启用 Gzip 压缩
            $response->header('Content-Encoding', 'gzip');
            
            // 设置缓存控制
            $response->header('Cache-Control', 'public, max-age=31536000');
            
            // 启用 Keep-Alive
            $response->header('Connection', 'keep-alive');
            
            // 设置安全头
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            
            // 启用 HTTP/2 服务器推送
            $response->header('Link', '</css/app.css>; rel=preload; as=style\n' .
                                   '</js/app.js>; rel=preload; as=script');
        }

        return $response;
    }

    /**
     * 获取缓存的响应
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getCachedResponse($key)
    {
        return Cache::get($key);
    }

    /**
     * 缓存响应
     *
     * @param  string  $key
     * @param  mixed  $response
     * @param  int  $minutes
     * @return void
     */
    protected function cacheResponse($key, $response, $minutes = 60)
    {
        Cache::put($key, $response, $minutes * 60);
    }

    /**
     * 检查请求是否可缓存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isCacheable(Request $request)
    {
        return $request->isMethod('GET') && !$request->ajax() && !auth()->check();
    }
} 