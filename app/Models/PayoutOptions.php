<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutOptions extends Model
{
    use HasFactory;
    protected $table = 'payout_options';

    protected $fillable = [
        'option_value', 'option_name', 'unique_model_name'
    ];
}
