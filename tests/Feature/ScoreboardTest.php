<?php

namespace Tests\Feature;

use App\Http\Controllers\ScoreboardController;
use App\Models\Player;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScoreboardTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');
    }

    /**
     * Shows scoreboard view
     *
     * @return void
     */
    public function test_shows_scoreboard_view()
    {
        //arrange

        //act
        $response = $this->get('/');

        //assert
        $response->assertViewIs('scoreboard');
        $response->assertStatus(200);
    }

    /**
     * Player 1 can score a point.
     *
     * @return void
     */
    public function test_player1_can_score()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 0;

        //act
        $scoreboard->player1Point($scoreboard->player1);

        //assert
        $this->assertEquals(1, $scoreboard->player1->score);
    }

    /**
     * Player 2 can score a point.
     *
     * @return void
     */
    public function test_player2_can_score()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player2->score = 0;

        //act
        $scoreboard->player2Point($scoreboard->player2);

        //assert
        $this->assertEquals(1, $scoreboard->player2->score);
    }

    /**
     * Scoreboard returns All suffix if scores are equal.
     *
     * @return void
     */
    public function test_scoreboard_returns_all_suffix_if_scores_are_equal()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1Point($scoreboard->player1);
        $scoreboard->player2Point($scoreboard->player2);

        //act
        $result = $scoreboard->scoreboard();

        //assert
        $this->assertStringEndsWith('All', $result);
    }

    /**
     * Scoreboard return does not include All suffix if scores are NOT equal.
     *
     * @return void
     */
    public function test_scoreboard_does_not_return_suffix_if_scores_are_not_equal()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1Point($scoreboard->player1);

        //act
        $result = $scoreboard->scoreboard();

        //assert
        $this->assertStringEndsNotWith('All', $result);
    }

    /**
     * Scoreboard return does not include All suffix if scores is Deuce.
     *
     * @return void
     */
    public function test_scoreboard_does_not_return_suffix_if_score_is_deuce()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 3;
        $scoreboard->player2->score = 3;

        //act
        $result = $scoreboard->scoreboard();

        //assert
        $this->assertStringContainsString('Deuce', $result);
        $this->assertDatabaseHas('scoreboards', [
            'deuce' => true,
        ]);
        $this->assertStringEndsNotWith('All', $result);
    }

    /**
     * Scoreboard returns deuce if scores are exactly 3-3
     *
     * @return void
     */
    public function test_scoreboard_returns_deuce_if_scores_are_3_all()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 3;
        $scoreboard->player2->score = 3;

        //act
        $result = $scoreboard->scoreboard();

        //assert
        $this->assertStringContainsString('Deuce', $result);
        $this->assertDatabaseHas('scoreboards', [
            'deuce' => true,
        ]);
    }

    /**
     * If score is Deuce and a player scores a point, advantage is returned
     *
     * @return void
     */
    public function test_scoreboard_returns_advantage_player_if_player_scores_when_decue_is_set()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 3;
        $scoreboard->player2->score = 3;
        $scoreboard->scoreboard();

        //act
        $this->post('/', [
            'playerScore' => 1
        ]);

        //assert
        $this->assertDatabaseHas('scoreboards', [
            'advantage' => 1,
        ]);
    }

    /**
     * If score is Advantage player 1 and player 2 scores a point, Deuce is returned
     *
     * @return void
     */
    public function test_scoreboard_returns_deuce_if_player_without_advantage_scores()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 3;
        $scoreboard->player2->score = 3;
        $scoreboard->scoreboard();
        $this->post('/', [
            'playerScore' => 1
        ]);

        //act
        $this->post('/', [
            'playerScore' => 2
        ]);


        //assert
        $this->assertStringContainsString('Deuce', $scoreboard->scoreboard());
        $this->assertDatabaseHas('scoreboards', [
            'deuce' => true,
        ]);
    }

    /**
     * Game is complete if score is greater than 3
     *
     * @return void
     */
    public function test_game_is_complete_if_score_is_greater_than_3()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 4;
        $scoreboard->player2->score = 3;
        $scoreboard->scoreboard();

        //act
        $result = $scoreboard->scoreboard();

        //assert
        $this->assertStringContainsString('Won by Player 1', $result);
        $this->assertDatabaseHas('scoreboards', [
            'complete' => true,
        ]);
    }

    /**
     * If player has advantage and scores they win
     *
     * @return void
     */
    public function test_player_wins_if_they_score_while_advantage()
    {
        //arrange
        $scoreboard = new ScoreboardController();
        $scoreboard->player1->score = 3;
        $scoreboard->player2->score = 3;
        $scoreboard->scoreboard();

        //act
        $this->post('/', [
            'playerScore' => 1
        ]);
        $this->followingRedirects()->post('/', [
            'playerScore' => 1
        ]);

        //assert
        $this->assertDatabaseHas('scoreboards', [
            'complete' => true,
            'display' => 'Won by Player 1',
        ]);
    }
}
