<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExportModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CHUNK_SIZE = 500;

    private string $filename;
    private string $directory;
    private string $modelClass;
    private array $select;
    private array $where;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id   User ID.
     * @param Carbon $now       Date when the job was dispatched.
     * @param string $filename  File name.
     * @param string $model     Model class string.
     * @param string $directory File directory.
     * @param array  $select    Model properties to export. Optional.
     * @param array  $where     Clauses which filter model records. Optional.
     */
    public function __construct(int $user_id, Carbon $now, string $filename, string $model, array $select = [], array $where = [])
    {
        $date = $now->format('Y-m-d');
        $this->directory = "exports/{$date}/{$user_id}/";
        $this->filename = $filename;
        $this->modelClass = $model;
        $this->select = $select;
        $this->where = $where;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!file_exists($this->directory)) {
            Storage::makeDirectory($this->directory);
        }

        if (Storage::exists($this->directory . $this->filename)) {
            Storage::delete($this->directory . $this->filename);
        }

        $model = new $this->modelClass();
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

        $stream = fopen(storage_path('app/' . $this->directory . $this->filename), 'w');
        fputcsv($stream, $headers);

        foreach ($query->lazy(self::CHUNK_SIZE) as $model) {
            fputcsv($stream, $model->toArray());
        }

        fclose($stream);
    }
}
