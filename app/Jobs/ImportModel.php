<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const ROW_LIMIT = 1000;
    const VALUE_LIMIT = 2000;

    private string $file_path;
    private object $model;
    private array $properties;

    /**
     * Create a new job instance.
     *
     * @param string $file_path  CSV file path relative to the application base path.
     * @param string $model      Model class string.
     * @param array  $properties Optional. Key values added to new model records.
     */
    public function __construct(string $file_path, string $model, array $properties = [])
    {
        $this->file_path = base_path($file_path);
        $this->model = new $model();
        $this->properties = $properties;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!file_exists($this->file_path)) {
            throw new \RuntimeException('File not found.');
        }

        $stream = fopen($this->file_path, 'r');
        $headers = [''];
        while (1 >= count($headers)) {
            $headers = fgetcsv($stream);
        }

        // Add extra headers.
        if ($this->properties) {
            array_push($headers, ...array_keys($this->properties));
        }

        // Determine the number of rows to insert with each database query.
        $batch_size = (int) floor(self::VALUE_LIMIT / count($headers));
        $batch = [];
        $line = 0;

        if (!$this->properties) {
            while(($record = fgetcsv($stream))) {
                $batch[] = array_combine($headers, $record);
                $line++;
                if ($batch_size === $line) {
                    $this->model::insert($batch);
                    $batch = [];
                    $line = 0;
                }
            }
        } else {
            // Add extra header values.
            $properties_values = array_values($this->properties);
            while(($record = fgetcsv($stream))) {
                array_push($record, ...$properties_values);
                $batch[] = array_combine($headers, $record);
                $line++;
                if ($batch_size === $line) {
                    $this->model::insert($batch);
                    $batch = [];
                    $line = 0;
                }
            }
        }

        fclose($stream);
        if ($batch) {
            $this->model::insert($batch);
        }

        unlink($this->file_path);
    }
}
