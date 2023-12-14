<?php

namespace App\Jobs;

use App\Http\Controllers\AdminAnggaran\DataAngController;
use App\Http\Controllers\Sirangga\Admin\BarangController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateStatusUsul implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updatestatushenti = new BarangController();
        $updatestatushenti = $updatestatushenti->aksiupdatestatususul();

    }
}
