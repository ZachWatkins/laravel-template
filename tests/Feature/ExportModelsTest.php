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

    public function testExportModels(): void
    {
        $user = User::factory()->create();
        $models = Model::factory()->count(10)->create(['user_id' => $user->id]);

        ModelsToCSV::dispatch(
            Model::class,
            'users',
            "{$user->id}/test-models.csv",
            [
                'select' => ['name', 'date', 'location', 'lat', 'long', 'user_id'],
                'where' => ['user_id', '=', $user->id],
                'orderBy' => 'id',
            ]
        );

        /** @var Illuminate\Filesystem\FilesystemAdapter */
        $disk = Storage::disk('users');
        $disk->assertExists("{$user->id}/test-models.csv");
        $disk->delete("{$user->id}/test-models.csv");
        $disk->assertNotExists("{$user->id}/test-models.csv");
    }
}
