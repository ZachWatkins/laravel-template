<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ZipUserFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $user_id;
    private string $source;
    private string $destination;
    private bool $delete_originals;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id          User ID.
     * @param string $destination      Destination zip file path.
     * @param string $app_storage_path File pattern matched within the `/storage/app/` directory.
     * @param string $delete_originals Whether to delete the original files after zipping.
     */
    public function __construct(int $user_id, string $destination, string $source, bool $delete_originals = true)
    {
        $this->user_id = $user_id;
        $this->source = $source;
        $this->destination = $destination;
        $this->delete_originals = $delete_originals;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Create the zip file.
        $zip = new \ZipArchive();
        $zip->open(
            storage_path("app/user/{$this->user_id}/{$this->destination}"),
            \ZipArchive::CREATE|\ZipArchive::OVERWRITE
        );

        // Add each user file to the archive.
        $files = Storage::allFiles($this->source);
        foreach ($files as $file) {
            $zip->addFile(
                storage_path('app/' . $file),
                str_replace("user/{$this->user_id}/", '', $file)
            );
        }

        $zip->close();

        // Delete original files.
        if ($this->delete_originals) {
            Storage::delete($this->source);
        }
    }
}
