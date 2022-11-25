<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'birthday',
      'birth_place',
      'address',
      'gender',
      'phone',
      'major_id',
      'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    protected function birthday(): Attribute{
        return Attribute::make(
          get: fn($value) => Carbon::createFromFormat('d/m/Y', $value)->format('d-m-Y'),
          set: fn($value) => Carbon::parse($value)->format('d/m/Y')
        );
    }

    public function scopeSearch($query, $keyword){
        return $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('birth_place', 'LIKE', '%'.$keyword.'%')
            ->orWhere('birthday', 'LIKE', '%'.$keyword.'%')
            ->orWhere('address', 'LIKE', '%'.$keyword.'%')
            ->orWhere('gender', 'LIKE', '%'.$keyword.'%')
            ->orWhere('phone', 'LIKE', '%'.$keyword.'%')
            ->orWhereHas('user', function($q) use($keyword){
                return $q->where('email', 'LIKE', '%'.$keyword.'%');
            })
            ->orWhereHas('major', function($q) use($keyword){
                return $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
    }
}
