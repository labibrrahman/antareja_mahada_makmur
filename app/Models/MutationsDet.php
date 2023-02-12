<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class MutationsDet extends Model
{
    protected $table = "detail_mutations";

    protected $fillable = [
    'mutasi_id',
    'asset_id',
    'description',
    ];
}