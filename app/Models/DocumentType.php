<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AppxpayDocument;
class DocumentType extends Model
{
    use HasFactory;

    protected $table = 'document_types';

    protected $fillable = [
        'document_name', 'document_type', 'mandatory_field',
    ];
    public function userDetails()
    {
        return $this->belongsTo(AppxpayDocument::class);
    }

}
