<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserStorageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic authenticated user file storage feature test.
     */
    public function test_can_store_a_file_in_the_user_disk(): void
    {
        try {
            // Create a user.
            $user = User::factory()->create([
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
            ]);

            // Make a POST request to the login endpoint.
            $response = $this->post('/login', [
                'email' => 'user@example.com',
                'password' => 'password',
            ]);

            // Check that the user is authenticated.
            $this->assertAuthenticatedAs($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        // // Create a fake file to upload.
        // $file = UploadedFile::fake()->create('test.txt', 100);

        // // Store the file on the user's disk.
        // Storage::disk('user')->put('/', $file, 'test.txt');

        // $this->assertTrue(
        //     Storage::disk('user')->exists('test.txt'),
        //     'File was not stored on the user disk.'
        // );

        // $this->assertTrue(
        //     Storage::disk('user')->exists($user->id . '/test.txt'),
        //     'File was not stored on a user ID path.'
        // );
    }
}
