<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'email',
        'date_of_birth',
    ];

    public function books()
    {
        return $this->hasMany('App\Models\Book');
    }
}
