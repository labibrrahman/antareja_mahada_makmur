<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Mutations extends Model
{
    protected $table = "mutations";

    protected $fillable = [
    'user_id',
    'status',
    ];
}