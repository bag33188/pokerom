<?php

namespace App\Observers;

use App\Events\AttemptRomLinkToRomFile;
use App\Models\Rom;

class RomObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = false;

    public function created(Rom $rom): void
    {
        AttemptRomLinkToRomFile::dispatch($rom);
    }

    public function updated(Rom $rom): void
    {
        AttemptRomLinkToRomFile::dispatchIf($rom->has_file === FALSE, $rom);
        $rom->refresh(); // <== this works (from observer class)
    }

    public function deleting(Rom $rom): void
    {
        // for unique constraint purposes
        $rom->file_id = NULL;
    }

    public function deleted(Rom $rom): void
    {
        if (config(USE_SQL_TRIGGERS) === false) {
            $rom->game()->delete();
        }
    }
}
