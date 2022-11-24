<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function students(){
        return $this->hasMany(Student::class);
    }

    protected function name(): Attribute{
        return Attribute::make(
          get: fn($value) => strtoupper($value),
          set: fn($value) => strtolower($value)
        );
    }
}
