<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoreboard extends Model
{
//    use HasFactory;

    protected $fillable = [
        'display',
        'suffix',
        'deuce',
        'advantage',
        'complete',
    ];

}
