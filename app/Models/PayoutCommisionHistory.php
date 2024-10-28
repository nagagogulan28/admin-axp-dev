<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutCommisionHistory extends Model
{
    use HasFactory;

    protected $table = 'payout_commision_history';

    protected $primaryKey = 'id';

    public $timestamps = true;
}