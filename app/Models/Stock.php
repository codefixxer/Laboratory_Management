<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';
    protected $primaryKey = 'itmId';
    protected $fillable = ['userId','itemName','itemDetail','expDate','itmQnt','itmPrice','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
