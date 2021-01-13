<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    //
	 public function nomineeList()
    {
        return $this->belongsTo(NomineeList::class,'nominee_list_id','id');
    }
}
