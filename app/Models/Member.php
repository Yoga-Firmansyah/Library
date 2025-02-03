<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sex',
        'email',
        'phone_number',
        'address'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->with('transactionDetails');
    }
}
