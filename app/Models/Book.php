<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author'
    ];

    protected $guarded  = [
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
}
