<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankDetails;

class BankAccountType extends Model
{
    use HasFactory;

    protected $table = 'bank_account_types';

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'name'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the bank details for the bank account type.
     */
    public function bankDetails()
    {
        return $this->hasMany(BankDetails::class, 'account_type')->where('status', '1');
    }
}