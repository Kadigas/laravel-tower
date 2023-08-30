<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $guard = 'default';
    protected $tables = 'rooms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'roomname',    
        'floornum',
        'capacity',
    ];
}
