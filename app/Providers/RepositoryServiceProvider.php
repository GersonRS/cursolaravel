<?php

namespace Ecommerce\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Ecommerce\Repositories\CategoryRepository',
            'Ecommerce\Repositories\CategoryRepositoryEloquent'
        );

        $this->app->bind(
            'Ecommerce\Repositories\ProductRepository',
            'Ecommerce\Repositories\ProductRepositoryEloquent'
        );
        
        $this->app->bind(
        		'Ecommerce\Repositories\ClientRepository',
        		'Ecommerce\Repositories\ClientRepositoryEloquent'
        );
        
        $this->app->bind(
        		'Ecommerce\Repositories\OrderRepository',
        		'Ecommerce\Repositories\OrderRepositoryEloquent'
        );
        
        $this->app->bind(
        		'Ecommerce\Repositories\OrderItemtRepository',
        		'Ecommerce\Repositories\OrderItemRepositoryEloquent'
        );
        
        $this->app->bind(
        		'Ecommerce\Repositories\UserRepository',
        		'Ecommerce\Repositories\UserRepositoryEloquent'
        );
        
        $this->app->bind(
        		'Ecommerce\Repositories\CupomRepository',
        		'Ecommerce\Repositories\CupomRepositoryEloquent'
        );
    }
}
