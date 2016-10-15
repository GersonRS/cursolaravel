<?php

namespace Ecommerce\Transformers;

use League\Fractal\TransformerAbstract;
use Ecommerce\Models\Client;

/**
 * Class ClientTransformer
 * @package namespace Ecommerce\Transformers;
 */
class ClientTransformer extends TransformerAbstract
{

    /**
     * Transform the \Client entity
     * @param \Client $model
     *
     * @return array
     */
    public function transform(Client $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->user->name,
            'email'      => $model->user->email,
            'phone'      => $model->phone,
            'address'    => $model->address,
            'city'       => $model->city,
            'state'      => $model->state,
            'zipcode'    => $model->zipcode,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
