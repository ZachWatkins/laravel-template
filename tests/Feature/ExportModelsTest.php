<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ModelsToCSV;
use App\Models\Model;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExportModelsTest extends TestCase
{
    use RefreshDatabase;

    protected const QUERY = [
        'select' => ['name', 'date', 'location', 'lat', 'long', 'user_id'],
        'where' => ['user_id', '=', '{user_id}'],
        'orderBy' => 'id',
    ];
    protected const MODEL_COUNT = 10;
    protected const DISK = 'users';

    public function test_export_models(): void
    {
        // Set up.
        // Define the disk type to avoid linting errors.
        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $disk = Storage::disk(self::DISK);

        $user = User::factory()->create();
        $models = Model::factory()
            ->count(self::MODEL_COUNT)
            ->create(['user_id' => $user->id]);
        $query = self::QUERY;
        $query['where'][2] = $user->id;

        $this->assertEquals(self::MODEL_COUNT, Model::count());

        $this->assertTrue(
            $disk->directoryMissing($user->id),
            'The user directory should not exist before the test.'
        );

        $this->assertTrue(
            $disk->fileMissing("{$user->id}/test-models.csv"),
            'The CSV file should not exist before the test.'
        );

        ModelsToCSV::dispatch(
            Model::class,
            self::DISK,
            "{$user->id}/test-models.csv",
            $query
        );

        $this->assertTrue(
            $disk->directoryExists($user->id),
            'The user directory should exist.'
        );
        $this->assertTrue(
            $disk->fileExists("{$user->id}/test-models.csv"),
            'The CSV file should exist.'
        );

        $contents = $disk->get("{$user->id}/test-models.csv");

        $lines = count(explode(PHP_EOL, $contents));
        $this->assertEquals(
            self::MODEL_COUNT + 2,
            $lines,
            'The CSV file should have '
            . self::MODEL_COUNT
            . ' rows of data, one header row, and one blank line.'
        );

        $this->assertTrue(
            $disk->deleteDirectory("{$user->id}/"),
            'The user directory should be deleted after the test.'
        );
    }
}
