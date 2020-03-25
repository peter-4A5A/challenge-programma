<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function companyInfo() {
      return $this->hasOne('App\CompanyInfo', 'user_id', 'id');
    }

    public function studentInfo() {
      return $this->hasOne('App\StudentInfo', 'user_id', 'id');
    }

    public function events() {
        return $this->belongsToMany('App\Event', 'student_event','student_id', 'event_id');
    }

}
