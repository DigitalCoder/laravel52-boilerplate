<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    // This table has a status column
    use HasStatus, SoftDeletes;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Checks whether this user is an administrator
    *
    * @param string The user type
    * @return boolean
    */
    public function isAdmin()
    {
        return ($this->type === 'admin');
    }

    /**
    * Checks whether this user is of the supplied type
    *
    * @param string The user type
    * @return boolean
    */
    public function isType($userType)
    {
        $types = explode(',', $userType);
        $typeMatch = false;
        foreach ($types as $type) {
            $typeMatch = $typeMatch || ($this->type == $type);
        }
        return $typeMatch;
    }
}
