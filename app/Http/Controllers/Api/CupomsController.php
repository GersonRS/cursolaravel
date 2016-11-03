<?php

namespace Ecommerce\Http\Controllers\Api;

use Ecommerce\Http\Controllers\Controller;
use Ecommerce\Repositories\CupomRepository;

class CupomsController extends Controller
{

    private $repository;
    
    public function __construct(CupomRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        return $this->repository->
            skipPresenter(false)->
            findByCode($code);
    }

}