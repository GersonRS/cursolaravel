<?php

namespace Ecommerce\Http\Middleware;

use Closure;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Ecommerce\Repositories\UserRepository;

class OAuthCheckRole
{
    private $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $id = Authorizer::getResourceOwnerId();

        $user = $this->userRepository->find($id);

        if($user->role != $role){
            abort(403, 'Access Forbidden');
        }

        return $next($request);
    }
}
