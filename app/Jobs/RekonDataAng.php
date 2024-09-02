<?php

namespace App\Jobs;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\AdminAnggaran\RefstatusController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RekonDataAng implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $idrefstatus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idrefstatus)
    {
        $this->idrefstatus = $idrefstatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $idrefstatus = $this->idrefstatus;
        $rekondataang = new DataAngController();
        $rekondataang = $rekondataang->aksirekondataang($idrefstatus);
    }
}
