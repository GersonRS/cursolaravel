<?php

namespace Ecommerce\Http\Controllers\Api\Client;

use Illuminate\Http\Request;

use Ecommerce\Http\Controllers\Controller;
use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Repositories\UserRepository;
use Ecommerce\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Ecommerce\Http\Requests\CheckoutRequest;

class ClientCheckoutController extends Controller
{
	
	private $orderRepository;
	private $userRepository;
	private $service;	
	
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
        $clientId = $this->userRepository->find($id)->client->id;
        
        $orders = $this->orderRepository->with(['items'])->scopeQuery(function($query) use($clientId){
        	return $query->where('client_id',$clientId);
        })->paginate();
        
        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {
        $data = $request->all();
        $id = Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $data['client_id'] = $clientId;
        $o = $this->service->create($data);
        $orders = $this->orderRepository->with(['items'])->find($o->id);
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
        $o = $this->orderRepository->with(['client','items','cupom'])->find($id);
        // $o->items->each(function($item){
        //     $item->product;
        // });
        return $o;
    }
}
