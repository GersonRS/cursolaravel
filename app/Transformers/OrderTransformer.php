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
    protected $availableIncludes = [ 'client', 'cupom', 'items', 'deliveryman'];
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
            'total'         => (float) $model->total,
            'status'         => (int) $model->status,
            
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ];
    }
    protected function getArrayProductsNames(Collection $items)
    {
        $names = [];
        foreach($items as $item){
            $names[] = $item->product->name;
        }
        return $names;
    }
    protected function getStatusName($idStatus)
    {
        $statusName = "";
        switch($idStatus){
            case 0: {
                $statusName = "Aguardando pagamento";
                break;
            }
            case 1: {
                $statusName = "Saiu para entrega";
                break;
            }
            case 2: {
                $statusName = "Entregue";
                break;
            }
            case 3: {
                $statusName = "Cancelado";
                break;
            }
        }
        return $statusName;
    }
    public function includeClient(Order $model)
    {
        return $this->item($model->client, new ClientTransformer());
    }
    public function includeDeliveryman(Order $model)
    {
        return $this->item($model->deliveryman, new DeliverymanTransformer());
    }
    public function includeCupom(Order $model)
    {
        if(!$model->cupom){
            return null;
        }
        return $this->item($model->cupom, new CupomTransformer());
    }
    public function includeItems(Order $model)
    {
        return $this->collection($model->items, new OrderItemTransformer());
    }
}
