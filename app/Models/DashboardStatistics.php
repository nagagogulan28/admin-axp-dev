<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardStatistics extends Model
{
    use HasFactory;

    protected $table = 'dashboard_statistics';
    
    protected $primaryKey = 'id';
}
