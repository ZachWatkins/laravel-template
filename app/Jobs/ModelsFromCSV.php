<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ModelsFromCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const VALUE_LIMIT = 2000;
    const ROW_LIMIT = 1000;

    /**
     * Import models from a database to a CSV file on a disk.
     *
     * @param string $model The model to import.
     * @param string $disk The disk to import from.
     * @param string $source The source path to import from.
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
        protected string $source = 'models.csv',
        protected array $query = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $disk_root = config("filesystems.disks.{$this->disk}.root");

        $stream = fopen("$disk_root/$this->source", 'r');
        $headers = fgetcsv($stream);
        $limit = min((int) ceil(self::VALUE_LIMIT / count($headers)), self::ROW_LIMIT);
        $i = 0;
        $rows = [];

        while ($record = fgetcsv($stream)) {
            $rows[] = array_combine($headers, $record);
            $i++;
            if ($i >= $limit) {
                DB::table($this->model::TABLE)->insert($rows);
                $rows = [];
                $i = 0;
            }
        }

        fclose($stream);
    }
}
