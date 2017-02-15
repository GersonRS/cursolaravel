<?php

namespace Ecommerce\Http\Controllers;

use Illuminate\Http\Request;

use Ecommerce\Http\Requests;
use Ecommerce\Http\Controllers\Controller;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Ecommerce\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Ecommerce\Models\User;

class UsersController extends Controller
{

    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticated()
    {
        $id = Authorizer::getResourceOwnerId();
        return $this->userRepository->skipPresenter(false)->find($id);
    }

}
