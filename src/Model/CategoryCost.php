<?php

namespace MMoney\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryCost extends Model
{
    protected $fillable = [
        "name", "user_id"
    ];
}