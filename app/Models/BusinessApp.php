<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UsersBusinessDetail;

class BusinessApp extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'business_apps';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'business_id',
        'sid',
        'terminal_id',
        'app_name',
        'app_url',
        'order_prefix',
        'merchant_app_key',
        'webhook_url',
        'ip_whitelist',
        'is_active',
        'type',
        'aggregators_id'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'disabled_at',
    ];

    /**
     * Get the business that owns the app.
     */
    public function business()
    {
        return $this->belongsTo(UsersBusinessDetail::class, 'business_id');
    }
    
}

