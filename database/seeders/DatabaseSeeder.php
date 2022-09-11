<?php

namespace Database\Seeders;

use App\Models\Scoreboard;
use Illuminate\Database\Seeder;
use App\Models\Player;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $player1 = new Player;
         $player1->id = 1;
         $player1->name = 'Player 1';
         $player1->save();

         $player2 = new Player;
         $player2->id = 2;
         $player2->name = 'Player 2';
         $player2->save();

         $scoreboard = new Scoreboard();
         $scoreboard->save();
    }
}
