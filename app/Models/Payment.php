<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payId';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true; // We have created_at

    protected $fillable = [
        'customerId',
        'recieved',
        'pending',
    ];

    // Relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }
}
