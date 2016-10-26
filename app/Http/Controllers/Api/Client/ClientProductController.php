<?php

namespace Ecommerce\Http\Controllers\Api\Client;

use Illuminate\Http\Request;

use Ecommerce\Http\Controllers\Controller;
use Ecommerce\Repositories\ProductRepository;

class ClientProductController extends Controller
{
	
	private $repository;
	
	public function __construct(ProductRepository $repository)
	{
		$this->repository = $repository;
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->repository->skipPresenter(false)->all();
        return $products;
    }

}
