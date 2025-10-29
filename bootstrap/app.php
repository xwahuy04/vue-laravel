<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AbleCreateUser;
use App\Http\Middleware\AbleCreateOrder;
use App\Http\Middleware\AbleFinishOrder;
use App\Http\Middleware\AbleCreateUpdateItem;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'create-order' => AbleCreateOrder::class,
            'finish-order' => AbleFinishOrder::class,
            'create-user' => AbleCreateUser::class,
            'create-update-item' => AbleCreateUpdateItem::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
