<?php
// app/Console/Commands/CleanExpiredGraphs.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GraphSauvegarde;
use Carbon\Carbon;

class VideGraphExpire extends Command
{
    protected $signature = 'graphs:clean';
    protected $description = 'Clean up expired graphs';

    public function handle()
    {
        $deleted = GraphSauvegarde::where('date_expiration', '<', Carbon::now())->delete();
        $this->info("Supprimé {$deleted} graphique expiré.");
        
        return Command::SUCCESS;
    }
}