<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    protected $table = "uploads";

    protected $fillable = [
    'asset_id',
    'user_id',
    'upload_status',
    'upload_image',
    'location',
    'asset_condition',
    ];
}