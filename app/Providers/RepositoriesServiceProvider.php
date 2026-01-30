<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach ($this->repositoriesMapping() as $contract => $concrete) {
            $this->app->bind($contract, $concrete);
        }
    }

    private function repositoriesMapping(): array
    {
        return [
            RepositoryInterface::class => BaseRepository::class,
        ];
    }
}
