<?php namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiController;
use SocialGraph\User\Models\User;
use SocialGraph\User\Api\UserApi;

class UserApiController extends ApiController
{
    /**
     * @var UserApi
     */
    protected $api;

    /**
     * @param UserApi $api
     */
    public function __construct(UserApi $api)
    {
        $this->api = $api;
    }


    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function show(User $user)
    {
        return $this->result($user, 200);
    }

}
