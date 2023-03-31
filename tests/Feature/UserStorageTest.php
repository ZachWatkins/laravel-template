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

        // store the file in the "user" disk
        $path = Storage::disk('user')->put('/test.txt', 'test');

        fwrite(STDERR, $path . PHP_EOL);

        $this->assertTrue(
            Storage::disk('user')->exists('test.txt'),
            'File was not stored on the user disk.'
        );

        fwrite(STDERR, count(Storage::disk('user')->allFiles()) . PHP_EOL);
        foreach (Storage::disk('user')->allFiles() as $file) {
            fwrite(STDERR, $file . PHP_EOL);
        }
        $this->assertTrue(
            Storage::disk('user')->exists($user->id . '/test.txt'),
            'File was not stored on a user ID path.'
        );
    }
}
