<?php

namespace Ecommerce\Services;

use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Ecommerce\Repositories\CupomRepository;
use Ecommerce\Models\Order;

class OrderService {
	
	private $orderRepository;
	private $cupomRepository;
	private $productRepository;
	
	public function __construct(
			OrderRepository $orderRepository, 
			CupomRepository $cupomRepository, 
			ProductRepository $productRepository
			) {
		$this->orderRepository = $orderRepository;
		$this->cupomRepository = $cupomRepository;
		$this->productRepository = $productRepository;
	}
	public function update(array $data, $id) {
		$this->clientRepository->update ( $data, $id );
		
		$userId = $this->clientRepository->find ( $id, [ 
				'user_id' 
		] )->user_id;
		
		$this->userRepository->update ( $data ['user'], $userId );
	}
	public function create(array $data)
    {
        \DB::beginTransaction();
        try{
            $data['status'] = 0;
            if(isset($data['cupom_id']))
            {
                unset($data['cupom_id']);
            }
            if(isset($data['cupom_code'])){
                $cupom = $this->cupomRepository->findByField('code',$data['cupom_code'])->first();
                $data['cupom_id'] = $cupom->id;
                $cupom->used = 1;
                $cupom->save();
                unset($data['cupom_code']);
            }
            $items = $data['items'];
            unset($data['items']);
            $order = $this->orderRepository->create($data);
            $total = 0;
            foreach($items as $item) {
                $item['price'] = $this->productRepository->find($item['product_id'])->price;
                $order->items()->create($item);
                $total += $item['price'] * $item['amount'];
            }
            $order->total = $total;
            if (isset($cupom)) {
                $order->total = $total - $cupom->value;
            }
            $order->save();
            \DB::commit();
            return $order;
        }catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }


    public function updateStatus($id, $idDeliveryman, $status)
    {
        $order = $this->orderRepository->getByIdAndDeliverymanId($id,$idDeliveryman);
        $order->status = $status;

        switch ((int)$status){
            case 1:
                if(!$order->hash){
                    $order->hash = md5((new \DateTime())->getTimestamp());
                }
                $order->save();
                break;
            case 2:
                $user = $order->client->user;
                $order->save();
                $this->pushProcessor->notify([$user->device_token],[
                    'message' => "Seu pedido {$order->id} acabou de ser entergue"
                ]);
                break;
        }

        return $order;
    }
}