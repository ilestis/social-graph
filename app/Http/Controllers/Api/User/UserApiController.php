<?php namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiController;
use SocialGraph\User\Models\User;

class UserApiController extends ApiController
{

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
