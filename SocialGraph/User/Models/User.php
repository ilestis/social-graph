<?php namespace SocialGraph\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'firstname', 'surname', 'gender', 'age'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * User_Relationships table relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relationships()
    {
        return $this->hasMany('SocialGraph\User\Models\Relationship');
    }

    public function friends()
    {
        return $this->belongsToMany('SocialGraph\User\Models\User', 'user_relationships', 'relation_id');
    }
}