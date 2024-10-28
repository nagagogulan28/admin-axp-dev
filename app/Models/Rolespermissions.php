<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permissions;

class Rolespermissions extends Model
{
    protected $table = 'user_roles_permissions';

    public $primarykey = 'id';
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'status' ,'module_id', 'module_create', 'module_edit','module_delete', 'module_view'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    public function moduledata()
    {
        return $this->hasOne(Permissions::class, 'id', 'module_id');
    }
}
