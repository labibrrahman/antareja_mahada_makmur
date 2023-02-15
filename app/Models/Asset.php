<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Asset extends Model
{
    protected $table = "assets";

    protected $fillable = [
    'asset_number',
    'asset_serial_number',
    'asset_capitalized_on',
    'asset_manager',
    'asset_desc',
    'asset_quantity',
    'asset_po',
    'asset_status',
    'departement_id',
    'category_id',
    'count_id',
    'location',
    "asset_condition",
    ];
}