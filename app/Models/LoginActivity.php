<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAppxpay;
use App\Models\Employee;

class LoginActivity extends Model
{
    use HasFactory;
    
    protected $table = 'employee_login_activity';

    protected $fillable = [
       
        'id',
        'log_ipaddress', 
        'log_device', 
        'log_os', 
        'log_browser',
        'log_time', 
        'log_employee',
        'is_admin'
    ];
    
    public $timestamps = false;
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'log_employee', 'id');
    }

    public function adminUser()
    {
        return $this->hasOne(Employee::class, 'id', 'log_employee');
    }

    public function otherUser()
    {
        return $this->hasOne(UserAppxpay::class, 'id', 'log_employee');
    }
}
