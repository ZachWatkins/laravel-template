<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\UserStorage;
use Carbon\Carbon;

class ExportUserModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CHUNK_SIZE = 500;

    /**
     * Create a new job instance.
     *
     * @param int    $user_id     User ID.
     * @param string $destination Where to write the file to within the user's folder.
     * @param string $model       Model class string.
     * @param array  $select      Model properties to export. Optional.
     * @param array  $where       Clauses which filter model records. Optional.
     */
    public function __construct(
        private int $user_id,
        private string $destination,
        private string $model,
        private array $select = [],
        private array $where = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(UserStorage $storage): void
    {
        $storage->forUser($this->user_id)
            ->ensureDirectoryExists($this->destination)
            ->delete($this->destination);

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

        $stream = fopen($storage->dir . $this->destination, 'w');
        fputcsv($stream, $headers);

        foreach ($query->lazy(self::CHUNK_SIZE) as $model) {
            fputcsv($stream, $model->toArray());
        }

        fclose($stream);
    }
}
