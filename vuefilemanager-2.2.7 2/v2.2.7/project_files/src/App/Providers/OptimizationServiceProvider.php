<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class OptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 优化数据库查询
        DB::connection()->disableQueryLog();
        
        // 设置默认字符串长度
        Schema::defaultStringLength(191);
        
        // 禁用不必要的事件
        Event::forget('eloquent.booted: *');
        
        // 配置 Redis 缓存
        if (config('cache.default') === 'redis') {
            Cache::enableTags(['files', 'users', 'settings']);
        }
        
        // 配置会话
        config([
            'session.driver' => env('SESSION_DRIVER', 'redis'),
            'session.lifetime' => env('SESSION_LIFETIME', 120),
            'session.secure' => env('SESSION_SECURE_COOKIE', true),
            'session.same_site' => 'lax',
        ]);
        
        // 配置队列
        config([
            'queue.default' => env('QUEUE_CONNECTION', 'redis'),
            'queue.connections.redis.retry_after' => 90,
            'queue.connections.redis.block_for' => null,
        ]);
        
        // 配置文件系统缓存
        config([
            'filesystems.disks.local.cache' => [
                'store' => 'redis',
                'expire' => 600,
                'prefix' => 'filesystems',
            ],
        ]);
        
        // 优化路由缓存
        if ($this->app->environment('production')) {
            $this->app['router']->middleware(['cache.headers:public;max_age=2628000;etag']);
        }
        
        // 配置日志
        config([
            'logging.channels.daily.days' => 7,
            'logging.channels.daily.permission' => 0644,
        ]);
    }
} 