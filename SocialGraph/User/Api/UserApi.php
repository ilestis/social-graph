<?php namespace SocialGraph\User\Api;

use SocialGraph\User\Models\User;

class UserApi
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }
}