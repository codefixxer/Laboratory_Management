<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTest extends Model
{
    protected $table = 'customer_tests';
    protected $primaryKey = 'ctId';
    public $incrementing = true;
    protected $keyType = 'int';

    // Disable Laravel's automatic timestamps
     

    protected $fillable = [
        'addTestId',
        'customerId',
        'created_at',
        'testStatus',
        'reportDate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId', 'customerId');
    }


    public function test()
    {
        return $this->belongsTo(Test::class, 'addTestId');
    }
   
    public function testRange()
    {
        return $this->belongsTo(TestRange::class, 'testRangesId', 'testRangesId');
    }
    public function testReports()
{
    return $this->hasMany(TestReport::class, 'ctId', 'ctId');
}

public function payment()
{
    return $this->hasOne(Payment::class, 'ctId', 'ctId');
}

}
