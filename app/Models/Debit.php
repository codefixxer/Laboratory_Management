<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Debit extends Model
{
    use HasFactory;

    protected $table = 'debit';
    protected $primaryKey = 'debitAi';
    protected $fillable = ['userId','debitAmount','debitDetail','createdDate'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    
}
