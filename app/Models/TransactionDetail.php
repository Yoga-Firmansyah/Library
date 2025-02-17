<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 
        'book_id', 
        'qty'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
