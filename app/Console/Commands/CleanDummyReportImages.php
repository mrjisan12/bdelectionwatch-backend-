<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanDummyReportImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-dummy-report-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete dummy images from storage/app/public/reports/images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Scanning report images...');

        // 1️⃣ Get valid image filenames from DB
        $dbImages = DB::table('reports')
            ->whereNotNull('image')
            ->pluck('image')
            ->map(function ($path) {
                return basename($path); // extract filename only
            })
            ->toArray();

        // 2️⃣ Get all images from storage
        $files = Storage::disk('public')->files('reports/images');

        $deleted = 0;

        foreach ($files as $file) {
            $filename = basename($file);

            // 3️⃣ Delete if not found in DB
            if (!in_array($filename, $dbImages)) {
                Storage::disk('public')->delete($file);
                $this->line("🗑 Deleted: {$filename}");
                $deleted++;
            }
        }

        $this->info("✅ Cleanup completed. Total deleted: {$deleted}");
    }
}
