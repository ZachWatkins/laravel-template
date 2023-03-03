<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ArchiveUserFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $user_id;
    private array $files;
    private string $filename;
    private string $directory;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id  User ID who archived the files.
     * @param array  $files    Files to archive.
     * @param string $filename Zip file path.
     */
    public function __construct(int $user_id, string $filename)
    {
        $this->user_id = $user_id;
        $this->filename = $filename;
        $this->directory = "users/{$user_id}/";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Storage::exists($this->directory . $this->filename)) {
            Storage::delete($this->directory . $this->filename);
        }

        // Get list of user files.
        $files = Storage::allFiles($this->directory);

        // Create the zip file.
        $destination = storage_path('app/' . $this->directory . $this->filename);
        $zip = new \ZipArchive();
        $zip->open($destination, \ZipArchive::CREATE);

        // Add each user file to the archive.
        foreach ($files as $file) {
            $path = storage_path('app/' . $file);
            $archived_path = str_replace($this->directory, '', $file);
            $zip->addFile($path, $archived_path);
        }

        $zip->close();

        // Delete original files.
        foreach ($files as $file) {
            Storage::delete($file);
        }
    }
}
