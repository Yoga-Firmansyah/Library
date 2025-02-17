<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'date_start',
        'date_end',
        'status'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class)->with('book');
    }
}
