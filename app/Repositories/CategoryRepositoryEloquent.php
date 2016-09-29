<?php

namespace Ecommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ecommerce\Repositories\CategoryRepository;
use Ecommerce\Models\Category;
use Ecommerce\Validators\CategoryValidator;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace Ecommerce\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
	
	
	
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
