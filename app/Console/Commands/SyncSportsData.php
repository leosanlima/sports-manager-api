<?php

namespace App\Console\Commands;

use App\Facades\BallDontLie;
use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Console\Command;

class SyncSportsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sports-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync team, player and game data from the BallDontLie API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->syncTeams();
        $this->syncPlayers();
        $this->syncGames();

        $this->line("Finished! All sports data synced");
    }


    /**
     * Synchronizes team data with the local database.
     *
     *
     * @return void
     *
     * @throws \Exception If an error occurs during the synchronization process.
     */
    protected function syncTeams()
    {
        try {

            $this->line("Synchronizing teams data");

            $teams = BallDontLie::teams();

            if (!isset($teams['data']) || empty($teams['data'])) {
                $this->warn("no data found");
                return;
            }

            foreach ($teams['data'] as $teamData) {
                Team::updateOrCreate(
                    ['id' => $teamData['id']],
                    [
                        'conference' => $teamData['conference'],
                        'division' => $teamData['division'],
                        'city' => $teamData['city'],
                        'name' => $teamData['name'],
                        'full_name' => $teamData['full_name'],
                        'abbreviation' => $teamData['abbreviation'],
                    ]
                );
            }

            $this->info(' - Teams sync completed.');

        } catch (\Exception $e) {
            $this->error("Error syncing teams: " . $e->getMessage());
        }
    }

    /**
     * Synchronizes payers data with the local database.
     *
     * @return void
     *
     * @throws \Exception If an error occurs during the synchronization process.
     */
    protected function syncPlayers()
    {
        $cursor = 1;

        $this->line("Synchronizing player data");

        try {

            do {
                $players = Balldontlie::players($cursor);

                if (!isset($players['data']) || empty($players['data'])) {
                    $this->warn("no data found");
                    return;
                }

                foreach ($players['data'] as $player) {
                    Player::updateOrCreate(
                        ['id' => $player['id']],
                        [
                            'first_name' => $player['first_name'],
                            'last_name' => $player['last_name'],
                            'position' => $player['position'],
                            'height' => $player['height'],
                            'weight' => $player['weight'],
                            'jersey_number' => $player['jersey_number'],
                            'college' => $player['college'],
                            'country' => $player['country'],
                            'draft_year' => $player['draft_year'],
                            'draft_round' => $player['draft_round'],
                            'draft_number' => $player['draft_number'],
                            'team_id' => $player['team']['id'],
                        ]
                    );
                }

                $cursor = $players['meta']['next_cursor'] ?? null;

                $this->info("Synchronized players cursor $cursor.");

            } while ($cursor);

            $this->info(' - Players sync completed.');

        } catch (\Exception $e) {
            $this->error("Error syncing player: " . $e->getMessage());
        }
    }

    /**
     * Synchronizes Games data with the local database.
     *
     * @return void
     *
     * @throws \Exception If an error occurs during the synchronization process.
     */
    protected function syncGames()
    {
        $cursor = null;

        $this->line("Synchronizing game data");
        try {
            do {
                $games = Balldontlie::games($cursor);

                if (!isset($games['data']) || empty($games['data'])) {
                    $this->warn("no data found");
                    return;
                }

                foreach ($games['data'] as $game) {
                    Game::updateOrCreate(
                        ['id' => $game['id']],
                        [
                            'date' => $game['date'],
                            'season' => $game['season'],
                            'status' => $game['status'],
                            'period' => $game['period'],
                            'time' => $game['time'],
                            'postseason' => $game['postseason'],
                            'home_team_score' => $game['home_team_score'],
                            'visitor_team_score' => $game['visitor_team_score'],
                            'home_team' => json_encode($game),
                            'visitor_team' => json_encode($game),
                        ]
                    );
                }

                $cursor = $games['meta']['next_cursor'] ?? null;

                $this->info("Synchronized games cursor $cursor.");

            } while ($cursor);

            $this->info(' - Games sync completed.');

        } catch (\Exception $e) {
            $this->error("Error syncing Game: " . $e->getMessage());
        }
    }
}
