<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Admin extends Authenticatable
{
    use Notifiable, HasUuids;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Tell Laravel to use password_hash instead of password
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function sessions()
    {
        return $this->hasMany(Session::class, 'admin_id');
    }

    public function approvedClassReps()
    {
        return $this->hasMany(ClassRep::class, 'approved_by');
    }
}