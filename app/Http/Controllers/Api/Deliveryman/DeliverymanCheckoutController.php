<?php

namespace Ecommerce\Http\Controllers\Api\Deliveryman;

use Ecommerce\Events\GetLocationDeliveryman;
use Ecommerce\Models\Geo;
use Illuminate\Http\Request;

use Ecommerce\Http\Requests;
use Ecommerce\Http\Controllers\Controller;
use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Repositories\UserRepository;
use Ecommerce\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class DeliverymanCheckoutController extends Controller
{

	private $orderRepository;
	private $userRepository;
	private $service;
    private $with = ['client','items','cupom'];

	public function __construct(
			OrderRepository $orderRepository,
			UserRepository $userRepository,
			OrderService $service
			)
	{
		$this->orderRepository = $orderRepository;
		$this->userRepository = $userRepository;
		$this->service = $service;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Authorizer::getResourceOwnerId();
        $orders = $this->orderRepository
        ->skipPresenter(false)
        ->with($this->with)
        ->scopeQuery(function($query) use($id){
        	return $query->where('user_deliveryman_id',$id);
        })->paginate();

        return $orders;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $idDeliveryman = Authorizer::getResourceOwnerId();
        return $this->orderRepository
        ->skipPresenter(false)
        ->getByIdAndDeliverymanId($id,$idDeliveryman);
    }

    public function updateStatus(Request $request, $id)
    {
        $id_deliveryman = Authorizer::getResourceOwnerId();
        return $this->service->updateStatus($id, $id_deliveryman, $request->get('status'));

    }

    public function geo(Request $request, Geo $geo, $id){
        $idDeliveryman = Authorizer::getResourceOwnerId();
        $order = $this->orderRepository->getByIdAndDeliverymanId($id,$idDeliveryman);
        $geo->lat = $request->get('lat');
        $geo->long = $request->get('long');
        event(new GetLocationDeliveryman($geo,$order));
        return $geo;
    }
}
