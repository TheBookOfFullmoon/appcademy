<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'birthday', 'birth_place',
        'address', 'gender', 'phone',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
