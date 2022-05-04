<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Chanel;

class NextDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:nextday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create next day programs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Chanel::nextDayCommand();
    }
}
