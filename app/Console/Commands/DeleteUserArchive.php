<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUserArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:user-archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete archive users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Output to console
        $this->info('Deleting archive users...');

        // Delay the process for 3 seconds
        sleep(3);

        // Delete all archive users
        User::onlyTrashed()->forceDelete();

        // If it is finished, output to console
        $this->info('Archive users deleted!');
    }
}
