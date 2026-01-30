<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RateLimiterServiceProvider::class,
    App\Providers\RepositoriesServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
];
