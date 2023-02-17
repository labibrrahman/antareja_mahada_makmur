<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counts extends Model
{
    protected $table = "counts";

    protected $fillable = [
    'count',
    ];
}