<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NomineeList extends Model
{
    //
	public function Nominee()
    {
        return $this->hasMany(Nominee::class,'id','nominee_list_id');
    }
}
