<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Departement extends Model
{
    protected $table = "departments";

    protected $fillable = [
    'department',
    ];

    function user(){
        return $this->hasOne(User::class,'departement_id');
	}

}
