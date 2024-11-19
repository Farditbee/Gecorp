<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdatePromoStatus extends Command
{
    // Nama dan deskripsi command
    protected $signature = 'promo:update-status';
    protected $description = 'Update promo status to done if the current date is past the end date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Mendapatkan tanggal saat ini
        $today = Carbon::today();

        // Mengupdate status menjadi 'done' jika tanggal hari ini melewati tanggal 'sampai'
        DB::table('promo')
            ->where('sampai', '<', $today)
            ->where('status', '!=', 'done') // Pastikan hanya mengupdate status yang belum 'done'
            ->update(['status' => 'done']);

        $this->info('Promo status updated successfully.');
    }
}
