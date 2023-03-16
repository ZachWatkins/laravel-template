<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExportUserModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CHUNK_SIZE = 500;

    private string $user_id;
    private string $destination;
    private string $model;
    private array $select;
    private array $where;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id     User ID.
     * @param string $destination Where to write the file to within the user's folder.
     * @param string $model       Model class string.
     * @param array  $select      Model properties to export. Optional.
     * @param array  $where       Clauses which filter model records. Optional.
     */
    public function __construct(int $user_id, string $destination, string $model, array $select = [], array $where = [])
    {
        $this->user_id = $user_id;
        $this->destination = $destination;
        $this->model = $model;
        $this->select = $select;
        $this->where = $where;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->createMissingDirectories();
        $this->deleteExistingFile();

        $model = new $this->model();
        $query = $model;

        if ($this->select) {
            $query::select($this->select);
            $headers = $this->select;
            if ($this->where) {
                $query->where($this->where);
            }
        } else {
            $headers = \Illuminate\Support\Facades\Schema::getColumnListing($model->getTable());
            if ($this->where) {
                $query::where($this->where);
            }
        }

        $stream = fopen(storage_path("app/user/{$this->user_id}/{$this->destination}"), 'w');
        fputcsv($stream, $headers);

        foreach ($query->lazy(self::CHUNK_SIZE) as $model) {
            fputcsv($stream, $model->toArray());
        }

        fclose($stream);
    }

    /**
     * Create directories recursively if missing from the destination.
     */
    private function createMissingDirectories(): void
    {
        $directories = explode('/', "{$this->user_id}/{$this->destination}");
        // Remove the file name from the directory list.
        array_pop($directories);
        $current = 'user/';
        foreach ($directories as $directory) {
            $current .= $directory . '/';
            if (!Storage::exists($current)) {
                Storage::makeDirectory($current);
            }
        }
    }

    /**
     * Delete the destination file if it exists.
     *
     * @return void
     */
    private function deleteExistingFile(): void
    {
        $path = "user/{$this->user_id}/{$this->destination}";
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
