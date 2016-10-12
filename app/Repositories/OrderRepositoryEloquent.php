<?php

namespace Ecommerce\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Models\Order;
use Ecommerce\Validators\OrderValidator;
use Illuminate\Database\Eloquent\Collection;
use Ecommerce\Presenters\OrderPresenter;

/**
 * Class OrderRepositoryEloquent
 * @package namespace Ecommerce\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{

    public function getByIdAndDeliverymanId($id,$idDeliveryman)
    {
        $result = $this->with(['client', 'items', 'cupom'])->findWhere([
            'id' => $id,
            'user_deliveryman_id' => $idDeliveryman
        ]);
        
        $result = $result->first();
        if ($result) {
            $result->items->each(function($item){
                $item->product;
            });
        }
        return $result;
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
