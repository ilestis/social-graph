<?php namespace SocialGraph\User\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Relationship extends Model
{
    protected $table = 'user_relationships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'relation_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Source User of the relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('SocialGraph\User\Models\User', 'user_id', 'id');
    }

    /**
     * Target of the relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation()
    {
        return $this->belongsTo('SocialGraph\User\Models\User', 'relation_id', 'id');
    }

    /**
     * Find the friends of user's friends who aren't already friends
     *
     * @param $query
     * @param User $user
     * @return mixed
     */
    public function scopeIndirectFriends($query, User $user)
    {
        // Get the Ids of user's friends for filtering
        $friends = $user->relationships()->lists('relation_id');

        // Get relations of user's friends who aren't already friends
        return $query
            ->whereIn('user_id', $friends)
            ->whereNotIn('relation_id', $friends) // Don't show my friends in the results
            ->where('relation_id', '<>', $user->id)
            ->groupBy('relation_id');
    }

    /**
     * Search for people who know at last two friends of the user but aren't currently connected.
     *
     * @param $query
     * @param User $user
     * @return mixed
     */
    public function scopeFindSuggestions($query, User $user)
    {
        // Get the Ids of user's friends for filtering
        $friends = $user->relationships()->lists('relation_id');

        // ex query should look like: SELECT user_id, COUNT(*) as cpt FROM user_relationships where relation_id in () group by user_id having cpt >= 2;
        return $query
            ->select(['user_id', DB::raw('COUNT(*) as cpt')])
            ->with('user')
            ->whereIn('relation_id', $friends)
            ->where('user_id', '<>', $user->id) // Don't show myself in the results
            ->groupBy('user_id') // group by user
            ->having('cpt', '>=', '2'); // Filter those with 2 or more similar connections
        }
}