<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Employee;

class Role extends Model
{
    protected $table = 'role';

    public $primarykey = 'id';
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'created_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function userdata()
    {
        return $this->hasOne(Employee::class, 'id', 'created_by');
    }
}
