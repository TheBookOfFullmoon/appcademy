<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'sks', 'lecturer_id'
    ];

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function schedule(){
        return $this->hasOne(Schedule::class);
    }

    public function scopeSearch($query, $keyword){
        return $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('sks', '=', '%'.$keyword.'%')
            ->orWhereHas('schedule', function ($q) use($keyword){
                return $q->where('room', 'LIKE', '%'.$keyword.'%');
            })
            ->orWhereHas('schedule', function($q) use($keyword){
                return $q->where('day_name', 'LIKE', '%'.$keyword.'%');
            })
            ->orWhereHas('lecturer', function($q) use($keyword){
                return $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
    }
}
