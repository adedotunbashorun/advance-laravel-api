<?php

namespace Modules\Users\Entities;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\History\Traits\Historable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Historable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password',
    ];

    public $attachments = [
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRoleId($roleId)
    {
        return Role::whereId($roleId)->first();
    }

    /**
     * Checks if a user belongs to the given Role Name
     * @param  string $name
     * @return bool
     */

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);

        }
        return true;
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);

        }
        return true;
    }

    public function hasPermissionTo($permission)
    {
        if (is_string($permission)) {

            foreach($this->roles as $role){
                if($role->permissions->contains('name', $permission)) return true;
            }
            return false;

        }
    }

    /**
     * Check if the current user is activated
     * @return bool
     */
    public function isActivated()
    {
        return (bool) $this->is_active;
    }


    public function setEmailAttribute($value)
    {
        return $this->attributes['email'] = preg_replace('/\s/', '', strtolower($value));
    }

    public function setPasswordAttribute($query)
    {
        return $this->attributes['password'] = bcrypt($query);
    }

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function loans()
    {
        return $this->hasMany("Modules\Loans\Entities\Loan");
    }
}
