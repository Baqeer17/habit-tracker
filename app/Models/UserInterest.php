<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $fillable = ['session_id', 'user_id', 'topic', 'score'];

    //
}
