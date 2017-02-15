<?php

namespace Ecommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Models\Order;
use Ecommerce\Validators\OrderValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ecommerce\Presenters\OrderPresenter;

/**
 * Class OrderRepositoryEloquent
 * @package namespace Ecommerce\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{
    protected $skipPresenter = true;

    public function getByIdAndDeliverymanId($id,$idDeliveryman)
    {
      $result = $this->model
          ->where('id',$id)
          ->where('user_deliveryman_id',$idDeliveryman)
          ->first();
      if($result){
        return $this->parserResult($result);
      }
      throw (new ModelNotFoundException())->setModel(get_class($this->model));
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
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
        return OrderPresenter::class;
    }
}
