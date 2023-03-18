<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\UserStorage;

class ExpireUserFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $user_id;
    protected array $files;

    /**
     * Create a new job instance.
     *
     * @param int   $user_id The user ID to expire files for.
     * @param array $files   List of files to forcefully delete.
     */
    public function __construct(int $user_id, array $force = [])
    {
        $this->user_id = $user_id;
        $this->files = $force;
    }

    /**
     * Execute the job.
     */
    public function handle(UserStorage $storage): void
    {
        // Delete files if they are explicitly listed for deletion.
        foreach ($this->files as $file) {
            $storage->delete($file);
        }

        // Delete folders if they are older than 3 days.
        $expires = strtotime('now - 3 days');
        $files = glob($storage->fullPath('*'));

        foreach ($files as $file) {
            $date = strtotime(basename($file));
            if ($date < $expires) {
                $storage->deleteDirectory($date);
            }
        }
    }
}
