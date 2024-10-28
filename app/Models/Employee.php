<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Define the table if it's not the default 'employees'
    protected $table = 'employee';

    // Define the fields that can be mass assigned
    protected $fillable = [
        'employee_username',
        'first_name',
        'last_name',
        'designation',
        'personal_email',
        'official_email',
        'password',
        'mobile_no',
        'department',
        'user_type',
        'last_seen_at',
        'last_password_change',
        'remember_token',
        'ft_login',
        'twofa',
        'threefa',
        'mgs_count',
        'failed_attempts',
        'employee_status',
        'created_date',
        'created_user',
        'is_account_locked',
    ];


    // If timestamps are not used, disable them
    public $timestamps = true;

    // Define any relationships, for example:
    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class, 'log_employee', 'id');
    }
}
