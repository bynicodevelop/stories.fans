<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class MediaConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:convert {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de traiter les fichiers vidéos';

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
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '4G');

        $listBitrates = [
            "350K",
            "700K",
            "1200K",
            "2500K",
            "5000K",
        ];

        if (Storage::exists($this->argument('path'))) {
            $fileSize = $this->formatSizeUnits(Storage::size($this->argument('path')));

            $this->info("File size: {$fileSize}");

            $bar = $this->output->createProgressBar(100);

            $bar->start();

            $lowBitrate = (new X264())->setKiloBitrate(250);

            FFMpeg::open($this->argument('path'))
                ->exportForHLS()
                ->addFormat($lowBitrate)
                ->onProgress(function ($percentage) use ($bar) {
                    $bar->advance($percentage);
                })
                ->save('private/convertion/e666e2068d9d4ea220b6085ef765cba3-full.mp4');
        }



        return 0;
    }

    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
