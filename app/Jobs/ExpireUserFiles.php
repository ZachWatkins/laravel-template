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
        $storage->setUser($this->user_id);

        foreach ($this->files() as $file) {
            $storage->delete($file);
        }

        $storage->deleteFilesBefore('now - 3 days');
    }

    /**
     * Get the files to delete.
     *
     * @return \Generator
     */
    private function files(): \Generator
    {
        foreach ($this->files as $file) {
            yield $file;
        }
    }
}
