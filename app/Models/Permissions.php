<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rolespermissions;
use App\Employee;

class        Permissions extends Model
{
    protected $table = 'user_permissions';

    public $primarykey = 'id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modules', 'parentMenuId' , 'modulesType' , 'menuOrder' , 'menuIcon' , 'status', 'created_by'
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

    public function getPermissions($id = null)
    {
        $query = $this->hasMany(Rolespermissions::class, 'module_id');

        if ($id !== null) {
            $query->where('role_id', $id);
        }

        return $query->get(); // Retrieve the results with get() method
    }
}
