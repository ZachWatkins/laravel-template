<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Services\UserStorage;

class ZipUserFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id          User ID.
     * @param string $destination      Destination zip file path.
     * @param string $source           File pattern matched within the user's storage directory.
     * @param string $delete_originals Whether to delete the original files after zipping.
     */
    public function __construct(
        private int $user_id,
        private string $destination,
        private string $source,
        private bool $delete_originals = true
    ) {}

    /**
     * Execute the job.
     */
    public function handle(UserStorage $storage): void
    {
        $storage->ensureDirectoryExists($this->destination);

        // Create the zip file.
        $zip = new \ZipArchive();
        $zip->open(
            $storage->fullDir . $this->destination,
            \ZipArchive::CREATE|\ZipArchive::OVERWRITE
        );

        // Add each user file to the archive.
        foreach ($this->getFiles($storage) as $file) {
            $zip->addFile(
                storage_path('app/' . $file),
                str_replace($storage->dir, '', $file)
            );
        }

        $zip->close();

        // Delete original files.
        if ($this->delete_originals) {
            $storage->delete($this->source);
        }
    }

    /**
     * Handle each of a user's files, being mindful of memory consumption for large directories.
     *
     * @return \Generator
     */
    private function getFiles(UserStorage $storage): \Generator
    {
        foreach (Storage::allFiles($storage->dir . $this->source) as $file) {
            yield $file;
        }
    }
}
