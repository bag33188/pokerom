<?php

namespace App\Observers;

use App\Events\AttemptRomLink;
use App\Models\Rom;

class RomObserver
{
    private bool $useDbLogic = true;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = false;

    public function created(Rom $rom): void
    {
        AttemptRomLink::dispatch($rom);
    }

    public function updated(Rom $rom): void
    {
        AttemptRomLink::dispatchIf($rom->has_file === FALSE, $rom);
    }

    public function deleting(Rom $rom): void
    {
        // for unique constraint purposes
        $rom->file_id = NULL;
    }

    public function deleted(Rom $rom): void
    {
        if ($this->useDbLogic === false) {
            $rom->game()->delete();
        }
    }
}
