<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use App\Models\User;

class ExpireUserFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $user_id;
    protected array $files;

    /**
     * Create a new job instance.
     *
     * @param int   $user_id The user ID to expire files for.
     * @param array $files   List of files to forcefully delete.
     */
    public function __construct(int $user_id, array $force = [])
    {
        $this->user_id = $user_id;
        $this->files = $force;
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            new RateLimited('expireexports'),
            (new WithoutOverlapping($this->user_id))->expireAfter(180)->dontRelease(),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->files as $file) {
            Storage::delete("user/{$this->user_id}/{$file}");
        }

        $expires = strtotime('now - 3 days');
        $files = glob(storage_path("app/user/{$this->user_id}/*"));
        foreach ($files as $file) {
            $date = strtotime(basename($file));
            if ($date < $expires) {
                Storage::deleteDirectory("app/user/{$this->user_id}/{$date}");
            }
        }
    }
}
