<?php

namespace Ecommerce\Transformers;

use League\Fractal\TransformerAbstract;
use Ecommerce\Models\OrderItem;

/**
 * Class OrderItemTransformer
 * @package namespace Ecommerce\Transformers;
 */
class OrderItemTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["product"];

    /**
     * Transform the \OrderItem entity
     * @param \OrderItem $model
     *
     * @return array
     */
    public function transform(OrderItem $model)
    {
        return [
            'id'         => (int) $model->id,
            'product_id' => (int) $model->product_id,
            'price'      => (float) $model->price,
            'amount'     => (int) $model->amount,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
    public function includeProduct(OrderItem $model)
    {
        return $this->item($model->product, new ProductTransformer());
    }
}
