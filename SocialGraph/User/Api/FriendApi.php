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
     * @param User $user
     * @return array
     */
    public function indirect(User $user)
    {
        // Get all the friends of the user
        $data = [];

        // Ids of my friends for filtering
        $friends = $user->relationships()->lists('relation_id');

        // Find friends of my friends who aren't me or them
        $ignore = $friends;
        $ignore[] = $user->id;
        $relations = $this->relation->whereIn('user_id', $friends)->whereNotIn('relation_id', $ignore)->groupBy('relation_id')->get();

        foreach($relations as $friend){
            $data[] = $friend->relation;
        }

        return $data;
    }

    /**
     * Suggested friends:
     * Return people in the group who know 2 or more direct friends of the chosen person, but are not directly connected to her.
     * @param User $user
     */
    public function suggest(User $user)
    {
        // Get friends of the current user
        $friends = $user->relationships()->lists('relation_id');

        $data = [];
        // Search for people who know at least two friends of the list but aren't connected to the current user
        // SELECT user_id, COUNT(*) as cpt FROM user_relationships where relation_id in (1, 3) group by user_id having cpt >= 2;
        $users = $this->relation
            ->select(['user_id', DB::raw('COUNT(*) as cpt')])
            ->with('user')
            ->whereIn('relation_id', $friends)
            ->where('user_id', '<>', $user->id)
            ->groupBy('user_id')
            ->having('cpt', '>=', '2')
            ->get();
        foreach($users as $relation) {
            $data[] = $relation->user;
        }

        return $data;
    }
}