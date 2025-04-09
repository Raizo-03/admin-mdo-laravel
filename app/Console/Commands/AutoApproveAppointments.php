<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;


class AutoApproveAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:auto-approve'; // Command name

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically approve appointments after 5 minutes';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Automatically approve all appointments with status 'Pending'
        // that are 5 minutes old or older
        Appointment::where('status', 'Pending')
            ->where('created_at', '<=', now()->subMinutes(1)) // 5 minutes logic
            ->update(['status' => 'Approved']);

        $this->info('Appointments that were pending for more than 5 minutes have been approved.');
    }
}
