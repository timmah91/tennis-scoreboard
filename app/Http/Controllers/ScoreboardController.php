<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Scoreboard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ScoreboardController extends Controller
{
    public Player $player1;
    public Player $player2;
    public Scoreboard $scoreboard;

    public function __construct()
    {
        $this->player1 = Player::where('id', 1)->get()[0];
        $this->player2 = Player::where('id', 2)->get()[0];
        $this->scoreboard = Scoreboard::first()->get()[0];
    }

    /**
     * @return View
     */
    public function showGame()
    {
        $this->scoreboard();

        return view('scoreboard', [
            'scoreboard' => $this->scoreboard,
        ]);
    }

    /**
     * @param Request $request
     * @return Redirector
     */
    public function scoreAPoint(Request $request)
    {
        // if its advantage and the same player scores, game is complete
        if ($this->scoreboard->advantage > 0) {
            if ($this->scoreboard->advantage == $request->input('playerScore')) {
                $this->scoreboard->complete = true;
                $this->store();

                return redirect('/');
            } else {
            // other player scored, back to deuce, remove advantage and return
            $this->scoreboard->advantage = 0;
            $this->scoreboard->deuce = true;
            $this->store();

            return redirect('/');
            }
        }

        // check if we need to assign advantage
        if ($this->scoreboard->deuce) {
            $this->scoreboard->advantage = $request->input('playerScore');
            $this->scoreboard->deuce = false;
            $this->store();

            return redirect('/');
        }

        switch ($request->input('playerScore'))
        {
            case (1):
                $this->player1Point($this->player1);
                $this->player1->save();
                break;
            case (2):
                $this->player2Point($this->player2);
                $this->player2->save();
                break;
        }

        return redirect('/');
    }

    /**
     * @return bool
     */
    public function store() : bool
    {
        return $this->scoreboard->save();
    }

    /**
     * @return string
     */
    public function scoreboard() : string
    {
        // if score is higher than 3 we have a winner
        if ($this->player1->score >= 4 || $this->player2->score >= 4)
        {
            $this->scoreboard->complete = true;
        }

        if ($this->isComplete()) {
            if ($this->player1->score > $this->player2->score || $this->scoreboard->advantage == $this->player1->id) {
                $this->scoreboard->display = 'Won by ' . $this->player1->name;
            }
            if ($this->player2->score > $this->player1->score || $this->scoreboard->advantage == $this->player2->id) {
                $this->scoreboard->display = 'Won by ' . $this->player2->name;
            }
            $this->store();

            return $this->scoreboard->display;
        }

        // check if score is equal
        if ($this->scoreIsEqual($this->player1, $this->player2) && !$this->scoreboard->advantage)
        {
            // check for deuce
            if ($this->player1->score === 3)
            {
                $this->scoreboard->deuce = true;
                $this->scoreboard->display = 'Deuce';
                $this->store();

                return $this->scoreboard->display;
            }
            $this->scoreboard->deuce = false;
            $this->scoreboard->suffix = ' All';
            $this->scoreboard->display = $this->getFormattedScore($this->player1) . $this->scoreboard->suffix;
            $this->store();

            return $this->scoreboard->display;
        }

        // does either player have advantage?
        if ($this->scoreboard->advantage > 0)
        {
            switch ($this->scoreboard->advantage) {
                case (1):
                    $this->scoreboard->display = 'Advantage ' . $this->player1->name;
                    break;
                case (2):
                    $this->scoreboard->display = 'Advantage ' . $this->player2->name;
                    break;
            }
            $this->store();

            return $this->scoreboard->display;
        }


        // not equal or deuce or advantage, return the scores
        $this->scoreboard->display = $this->getFormattedScore($this->player1) . "-" .  $this->getFormattedScore($this->player2);
        $this->store();

        return $this->scoreboard->display;
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return bool
     */
    public function scoreIsEqual(Player $player1, Player $player2) : bool
    {
        return $player1->score === $player2->score;
    }

    /**
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->scoreboard->complete;
    }

    /**
     * @param Player $player1
     * @return void
     */
    public function player1Point(Player $player1): void
    {
        $player1->score++;
    }

    /**
     * @param Player $player2
     * @return void
     */
    public function player2Point(Player $player2): void
    {
        $player2->score++;
    }

    /**
     * @param Player $player
     * @return string
     */
    public function getFormattedScore(Player $player) : string
    {
        switch ($player->score) {
            case (0):
                $player->formattedScore = 'Love';
                break;
            case (1):
                $player->formattedScore = 'Fifteen';
                break;
            case (2):
                $player->formattedScore = 'Thirty';
                break;
            case (3):
                $player->formattedScore = 'Forty';
                break;
        }

        $player->save();
        return $player->formattedScore;
    }
}
