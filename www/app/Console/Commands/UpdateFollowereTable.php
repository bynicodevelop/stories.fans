<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateFollowereTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:update_followers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Permet de s'assurer que l'utilisateur inscrit s'auto-follow";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // DB::enableQueryLog();

        $users = User::all();

        $emptyFollowers = $users->filter(function ($u) {
            $usr = $u->getFollowers()->where('follow_id', $u['id'])->first();

            return empty($usr);
        });

        Log::info("Number user who don't auto follow", [
            "n" => count($emptyFollowers)
        ]);

        $emptyFollowers->each(function ($u) {
            $u->follow($u);
        });

        // dd(DB::getQueryLog());

        return 0;
    }
}
