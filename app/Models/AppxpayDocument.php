<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserAppxpay;
use App\Models\DocumentType;

class AppxpayDocument extends Model
{
    use HasFactory;

    protected $table = 'appxpay_documents'; // Specify the table name

    protected $fillable = [
        'user_id',
        'document_id',
        'base_url',
        'document_path',
    ];

    /**
     * Get the user that owns the document.
     */
    public function userDetails()
    {
        return $this->belongsTo(UserAppxpay::class);
    }
    public function documentType()
    {
        return $this->hasOne(DocumentType::class, 'id', 'document_id');
    }
}
