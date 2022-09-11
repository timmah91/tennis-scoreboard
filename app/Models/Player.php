<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Post
 *
 * @mixin Eloquent
 */
class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'score',
        'advantage',
        'formattedScore',
    ];

//    public function scoreboard() : Scoreboard
//    {
//        return $this->belongsTo(Scoreboard::class, 'scoreboard_id', 'id');
//    }


}
