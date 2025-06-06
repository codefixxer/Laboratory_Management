<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Credit extends Model
{
    use HasFactory;

    protected $table = 'credit';
    protected $primaryKey = 'creditAi';
    protected $fillable = ['userId','creditAmount','creditDetail','created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
