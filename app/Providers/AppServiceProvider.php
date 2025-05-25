<?php

namespace App\Providers;

use App\Repositories\RepositoryClasses\BankRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\RepositoryInterfaces\CategoryInterface;
use App\Repositories\RepositoryClasses\CategoryRepository;
use App\Repositories\RepositoryClasses\SubCategoryRepository;
use App\Repositories\RepositoryInterfaces\BrandInterface;
use App\Repositories\RepositoryClasses\BrandRepository;
use App\Repositories\RepositoryInterfaces\BankInterface;
use App\Repositories\RepositoryInterfaces\SubCategoryInterface;
use App\Repositories\RepositoryInterfaces\BranchInterface;
use App\Repositories\RepositoryClasses\BranchRepository;
use App\Repositories\RepositoryInterfaces\CustomerInterfaces;
use App\Repositories\RepositoryClasses\CustomerRepository;
use App\Repositories\RepositoryInterfaces\EmployeeInterface;
use App\Repositories\RepositoryInterfaces\DamageInterface;
use App\Repositories\RepositoryClasses\EmployeeRepository;
use App\Repositories\RepositoryClasses\DamageRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class,CategoryRepository::class);
        $this->app->bind(BrandInterface::class,BrandRepository::class);
        $this->app->bind(SubCategoryInterface::class,SubCategoryRepository::class);
        $this->app->bind(BankInterface::class,BankRepository::class);
        $this->app->bind(BranchInterface::class,BranchRepository::class);
        $this->app->bind(CustomerInterfaces::class,CustomerRepository::class);
        $this->app->bind(EmployeeInterface::class,EmployeeRepository::class);
        $this->app->bind(DamageInterface::class,DamageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
