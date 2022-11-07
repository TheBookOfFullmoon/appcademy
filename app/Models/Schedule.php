<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
      'day_name', 'room', 'subject_id'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}
