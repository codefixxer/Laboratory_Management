<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestReport extends Model
{
    use HasFactory;

    protected $table = 'testreport';
    protected $primaryKey = 'reportId';
    protected $fillable = [
        'ctId',
        'reporterId', // ✅ Ensure this exists
        'signStatus',
        'created_at',
    ];

    public function customerTest()
    {
        return $this->belongsTo(CustomerTest::class, 'ctId', 'ctId');
    }

    public function testReportChildren()
    {
        return $this->hasMany(TestReportChild::class, 'reportId', 'reportId');
    }
    public function reportChildren()
{
    return $this->hasMany(TestReportChild::class, 'reportId', 'reportId');
}
public function payment()
{
    return $this->hasOne(Payment::class, 'ctId', 'ctId');
}
public function testRange()
{
    return $this->belongsTo(TestRange::class, 'testRangeId', 'testRangeId');
}

}
