<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customerId';
    public $incrementing = true;
    protected $keyType = 'int';

    // If you do NOT want to use Eloquent's default timestamps:
      // because we have created_at

    protected $fillable = [
        'userId',
        'relation',
        'title',
        'user_name',
        'password',
        'name',
        'email',
        'phone',
        'gender',
        'age',
        'lcStatus',
        'extPanelId',
        'addRefrealId',
        'staffPanelId',
        'comment',
        'testDiscount',
    ];

        public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }
    // Example relationships
    public function tests()
    {
        return $this->hasMany(CustomerTest::class, 'customerId', 'customerId');
    }

public function user()
{
    return $this->belongsTo(User::class, 'userId');
}
    public function payments()
    {
        return $this->hasMany(Payment::class, 'customerId', 'customerId');
    }
    public function customerTests()
    {
        return $this->hasMany(CustomerTest::class, 'customerId');
    }
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($customer) {
    //         $customer->password = Str::random(10); 
    //     });
    // }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'customerId', 'customerId');
    }
    

    public function receptionist()
    {
        return $this->belongsTo(\App\Models\User::class, 'userId', 'id');
    }

    






public function staffPanel()
{
    return $this->belongsTo(StaffPanel::class, 'staffPanelId', 'staffPanelId');
}

public function externalPanel()
{
    return $this->belongsTo(ExternalPanel::class, 'extPanelId', 'extPanelId');
}

public function referral()
{
    return $this->belongsTo(\App\Models\Referral::class, 'addRefrealId', 'id');
}

}
