<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $guard = 'default';
    protected $tables = 'ruangans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'building',
        'roomname',    
        'floornum',
        'capacity',
        'description',
        'picture',
    ];

    public function staff(){
        return $this->hasOne(Staff::class);
    }
}
