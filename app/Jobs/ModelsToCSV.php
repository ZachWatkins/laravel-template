<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ModelsToCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CHUNK_SIZE = 500;

    /**
     * Export models from a database to a CSV file on a disk.
     *
     * @param string $model The model to export.
     * @param string $disk The disk to export to.
     * @param string $destination The destination path to export to.
     * @param array  $query {
     *     The query to apply to the model (optional).
     *     @type array        $select  The Select clause.
     *     @type array        $where   The where clause.
     *     @type string|array $orderBy The orderBy clause. Accepts a column
     *                                 name or an array of arrays of column
     *                                 names and directions ('asc', 'desc').
     * }
     */
    public function __construct(
        protected string $model,
        protected string $disk,
        protected string $destination = 'models.csv',
        protected array $query = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $disk_root = config("filesystems.disks.{$this->disk}.root");
        $model = new $this->model();
        $csv_headers = $this->query['select'] ?? Schema::getColumnListing($model->getTable());

        // Define the database query.
        $eloquent = $model::select($this->query['select'] ?? '*');
        if (isset($this->query['where'])) {
            $eloquent->where($this->query['where']);
        }
        if (isset($this->query['orderBy'])) {
            if (is_string($this->query['orderBy'])) {
                $eloquent->orderBy($this->query['orderBy']);
            } else {
                foreach ($this->query['orderBy'] as $order) {
                    $eloquent->orderBy($order[0], $order[1]);
                }
            }
        }

        $disk = Storage::disk($this->disk);
        $disk->makeDirectory(dirname($this->destination));

        $stream = fopen("$disk_root/$this->destination", 'w');

        fputcsv($stream, $csv_headers);

        foreach ($eloquent->lazy(self::CHUNK_SIZE) as $model) {
            fputcsv($stream, $model->toArray());
        }

        fclose($stream);
    }
}
