<?php namespace App\Http\Controllers\Api\User;

/**
 * Written by Jeremy Payne
 */

use App\Http\Controllers\Api\ApiController;
use SocialGraph\User\Models\User;
use SocialGraph\User\Api\FriendApi;

class FriendApiController extends ApiController
{
    /**
     * @var FriendApi
     */
    protected $api;

    /**
     * @param UserApi $api
     */
    public function __construct(FriendApi $api)
    {
        $this->api = $api;
    }


    /**
     * Get the direct friends of a user
     *
     * @return Response
     */
    public function friends(User $user)
    {
        return $this->result($this->api->friends($user), 200);
    }

    /**
     * Get the indirect friends (friends of friends) of a user.
     *
     * @return Response
     */
    public function indirect(User $user)
    {
        return $this->result($this->api->indirect($user), 200);
    }

    /**
     * Get suggested friends for a user.
     *
     * @return Response
     */
    public function suggest(User $user)
    {
        return $this->result($this->api->suggest($user), 200);
    }

}
