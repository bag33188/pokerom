<?php

namespace App\Jobs;

use App\Services\RomFileProcessor;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRomFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $romFilename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $romFilename)
    {
        $this->romFilename = $romFilename;
    }

    /**
     * Execute the job.
     *
     * @param RomFileProcessor $romFileProcessor
     * @return void
     * @throws Exception
     */
    public function handle(RomFileProcessor $romFileProcessor): void
    {
        $romFileType = strtoupper(explode('.', $this->romFilename, 2)[1]);
        $romFileProcessor->upload($this->romFilename, [
            'contentType' => 'application/octet-stream',
            'romType' => $romFileType
        ]);
    }
}
