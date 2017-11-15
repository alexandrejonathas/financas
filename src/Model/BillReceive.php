<?php

namespace MMoney\Model;

use Illuminate\Database\Eloquent\Model;

class BillReceive extends Model
{
    protected $fillable = [
        "date_launch",
        "name",
        "value",
        "user_id"
    ];
}