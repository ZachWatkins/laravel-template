<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ArchiveModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Moves models from their default database table to an archival table.
     *
     * @param User $user The user who archived the model.
     * @param int $creatorId The id of the user who created the model.
     * @param string $startDate The start date of the archival period.
     * @param string $endDate The end date of the archival period.
     */
    public function __construct(
        protected User $user,
        protected int $creatorId,
        protected string $startDate,
        protected string $endDate
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            // Step 1: Insert archive table records that are not already archived.
            DB::insert(
                'INSERT INTO archived_models (name, date, location, lat, long, created_at, updated_at, user_id, archived_by)
                SELECT name, date, location, lat, long, created_at, ?, user_id, ? FROM models
                WHERE user_id = ?
                AND date BETWEEN ? AND ?
                AND NOT EXISTS (
                    SELECT 1 FROM archived_models
                    WHERE archived_models.user_id = models.user_id
                    AND archived_models.name = models.name
                    AND archived_models.date = models.date
                    AND archived_models.location = models.location
                )',
                [now(), $this->user->id, $this->creatorId, $this->startDate, $this->endDate]
            );

            // Step 2: Update archive table records that we are re-archiving.
            DB::update(
                'UPDATE archived_models
                SET lat = models.lat, long = models.long, created_at = models.created_at, updated_at = ?, archived_by = ?
                FROM models
                WHERE archived_models.user_id = models.user_id
                AND archived_models.name = models.name
                AND archived_models.date = models.date
                AND archived_models.location = models.location
                AND models.date BETWEEN ? AND ?',
                [now(), $this->user->id, $this->startDate, $this->endDate]
            );

            // Step 3: Delete records from the default table that are now archived.
            DB::delete(
                'DELETE FROM models
                WHERE user_id = ?
                AND date BETWEEN ? AND ?',
                [$this->creatorId, $this->startDate, $this->endDate]
            );

            // Step 4: Check the table for any records that are not archived but should be.
            $remaining = DB::select(
                'SELECT count(*) FROM models
                WHERE user_id = ?
                AND date BETWEEN ? AND ?',
                [$this->creatorId, $this->startDate, $this->endDate]
            );

            if ($remaining > 0) {
                throw new \Exception('Some records were not archived.');
            }
        });
    }
}
