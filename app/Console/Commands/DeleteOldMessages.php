<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Messages;
class DeleteOldMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old messages';

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
     * @return mixed
     */
    public function handle()
    {
        $date = date('Y-m-d H:i:s', strtotime(' -1 day'));
        Messages::where('created_at','<',$date)->delete();
    }
}
