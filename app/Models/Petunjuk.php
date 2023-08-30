<?php


namespace App\Models;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Petunjuk extends Model
{
    use HasFactory;
    public $table = "petunjuk";
    public $timestamps = false;
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'file_path'
    ];
}