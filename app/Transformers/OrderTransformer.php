<?php

namespace Ecommerce\Transformers;

use League\Fractal\TransformerAbstract;
use Ecommerce\Models\Order;

/**
 * Class OrderTransformer
 * @package namespace Ecommerce\Transformers;
 */
class OrderTransformer extends TransformerAbstract
{
     protected $availableIncludes = ["cupom", "items", "client"];

    /**
     * Transform the \Order entity
     * @param \Order $model
     *
     * @return array
     */
    public function transform(Order $model)
    {
        return [
            'id'         => (int) $model->id,
            'total'      => (float) $model->total,
             'product_names' => $this->getArrayProductNames($model->items),
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
    protected function getArrayProductNames(Collection $items)
    {
        $names = [];
        foreach($items as $item)
        {
            $names[] = $item->product->name;
        }
        return $names;
    }
    public function includeCupom(Order $model)
    {
        if(!$model->cupom)
        {
            return null;
        }
        return $this->item($model->cupom, new CupomTransformer());
    }
    public function includeItems(Order $model)
    {
        if(!$model->items)
        {
            return null;
        }
        return $this->collection($model->items, new OrderItemTransformer());
    }
    public function includeClient(Order $model)
    {
        return $this->item($model->client, new ClientTransformer());
    }
}
