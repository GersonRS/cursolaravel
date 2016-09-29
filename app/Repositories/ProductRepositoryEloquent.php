<?php

namespace Ecommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ecommerce\Repositories\ProductRepository;
use Ecommerce\Models\Product;
use Ecommerce\Validators\ProductValidator;

/**
 * Class ProductRepositoryEloquent
 * @package namespace Ecommerce\Repositories;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
	public function getProducts()
	{
		return $this->model->get(['id','name','price']);
	}
	
	/**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
