<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMode extends Model
{
    use HasFactory;

    protected $table = 'transaction_modes';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
}