<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserStorageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic authenticated user file storage feature test.
     */
    public function test_can_store_a_file_in_the_users_disk(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Arrange
        $filename = 'test.txt';
        $file = UploadedFile::fake()->create($filename);

        Storage::fake('users');

        // Act
        Storage::disk('users')->putFileAs($user->id, $file, $filename);

        // Assert
        Storage::disk('users')->assertExists("$user->id/$filename");
    }
}
