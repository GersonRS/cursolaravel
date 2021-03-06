<?php

namespace Ecommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ecommerce\Repositories\UserRepository;
use Ecommerce\Models\User;
use Ecommerce\Validators\UserValidator;
use Ecommerce\Presenters\UserPresenter;

/**
 * Class UserRepositoryEloquent
 * @package namespace Ecommerce\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $skipPresenter = true;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function getDeliverymen()
    {
    	return $this->model->where(['role'=>'deliveryman'])->lists('name','id');
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return UserPresenter::class;
    }
}
