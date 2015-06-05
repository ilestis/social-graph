<?php namespace SocialGraph\User\Api;

use SocialGraph\User\Models\Relationship;
use SocialGraph\User\Models\User;

use DB;

class FriendApi
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Relation
     */
    protected $relation;

    /**
     * @param User $user
     * @param Relation $relation
     */
    public function __construct(User $user, Relationship $relation){
        $this->user = $user;
        $this->relation = $relation;
    }

    /**
     * Build an array with the friends of a user
     *
     * @param User $user
     * @return array
     */
    public function friends(User $user)
    {
        // Get all the friends of the user
        $data = [];

        foreach($user->friends as $friend){
            $data[] = $friend;
        }

        return $data;
    }


    /**
     * Build an array with the friends of friends of a user
     *
     * @param User $user
     * @return array
     */
    public function indirect(User $user)
    {
        // Get all the friends of the user
        $data = [];

        $relations = $this->relation->indirectFriends($user)->get();

        foreach($relations as $friend){
            $data[] = $friend->relation;
        }

        return $data;
    }

    /**
     * Suggested friends:
     * Return people in the group who know 2 or more direct friends of the chosen person, but are not directly connected to her.
     *
     * @param User $user
     */
    public function suggest(User $user)
    {
        $data = [];

        $users = $this->relation->findSuggestions($user)->get();
        foreach($users as $relation) {
            $data[] = $relation->user;
        }

        return $data;
    }
}