<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Filesystem\Filesystem;

class DeleteFilesBeforeDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected int $date;

    /**
     * Create a new job instance.
     *
     * @param Filesystem        $disk Disk to search in.
     * @param int|string|object $date Date to search before.
     * @param string            $path Path to search in.
     */
    public function __construct(
        protected Filesystem $disk,
        int|string|object $date = 'now',
        protected string $directory = '')
    {
        if (is_int($date)) {
            $this->date = $date;
        }
        $this->date = is_string($date) ? strtotime($date) : $date->getTimestamp();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->files() as $file) {
            $this->disk->delete($file);
        }
    }

    /**
     * Get the files to delete.
     *
     * @return \Generator
     */
    private function files(): \Generator
    {
        foreach ($this->disk->allFiles($this->directory) as $file) {
            if ($this->disk->lastModified($file) < $this->date) {
                yield $file;
            }
        }
    }
}
