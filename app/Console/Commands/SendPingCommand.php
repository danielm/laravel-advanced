<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Notifications\PingNotification;

class SendPingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Ping notificacion';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        $count = $users->count();

        if ($count > 0)
        {
            $this->info("About to send {$count} notifications.");
            
            $this->newLine(1);

            $this->output->progressStart($count);

            $users->each(function(User $user){
                $user->notify(new PingNotification());
                //sleep(2);

                $this->output->progressAdvance();
            });

            $this->output->progressFinish();

            $this->info("{$count} Notifications sent.");
        }

        

        return 0;
    }
}
