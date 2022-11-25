<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function birthday(): Attribute{
        return Attribute::make(
            get: fn($value) => Carbon::createFromFormat('d/m/Y', $value)->format('d-m-Y'),
            set: fn($value) => Carbon::parse($value)->format('d/m/Y')
        );
    }
}
