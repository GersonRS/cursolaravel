<?php

namespace Ecommerce\Http\Controllers;

use Illuminate\Http\Request;

use Ecommerce\Http\Requests;
use Ecommerce\Http\Controllers\Controller;
use Ecommerce\Repositories\OrderRepository;
use Ecommerce\Repositories\UserRepository;
use Ecommerce\Repositories\ProductRepository;
use Ecommerce\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Ecommerce\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
	
	private $orderRepository;
	private $userRepository;
	private $productRepository;
	private $service;	
	
	public function __construct(
			OrderRepository $orderRepository,
			UserRepository $userRepository, 
			ProductRepository $productRepository,
			OrderService $service
			)
	{
		$this->orderRepository = $orderRepository;
		$this->userRepository = $userRepository;
		$this->productRepository = $productRepository;
		$this->service = $service;
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientId = $this->userRepository->find(Auth::user()->id)->client->id;
        
        $orders = $this->orderRepository->scopeQuery(function($query) use($clientId){
        	return $query->where('client_id',$clientId);
        })->paginate();
        
        return view('customer.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->productRepository->getProducts();
        return view('customer.order.create',compact('products'));
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
        
        $clientId = $this->userRepository->find(Auth::user()->id)->client->id;
        
        $data['client_id'] = $clientId;
        
        $this->service->create($data);
        
        return redirect()->route('customer.order.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
