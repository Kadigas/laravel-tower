<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $guard = 'default';
    protected $tables = 'rooms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'staffname',
        'floornum',
        'roomname',    
        'contactnumber',
        'email',
    ];

    public function ruangan(){
        return $this->belongsTo(Ruangan::class);
    }
}
