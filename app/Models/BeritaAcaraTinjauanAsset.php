<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class BeritaAcaraTinjauanAsset extends Model
{
    protected $table = "ba_tinjauan_asset";

    protected $fillable = [
    'ba_number',
    'tgl_awal',
    'tgl_akhir',
    'departement_id',
    ];
}