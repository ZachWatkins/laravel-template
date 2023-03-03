<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $example = User::factory()->example()->make();
            $user = User::where('name', $example->name)->first();
        }

        $directory = "users/{$user->id}/";

        if (Storage::exists($directory . $request->input('file'))) {
            // Remove the file after it is downloaded.
            dispatch(function () use ($directory, $request) {
                Storage::delete($directory . $request->input('file'));
                if (empty(Storage::allFiles($directory))) {
                    Storage::deleteDirectory($directory);
                }
            })->afterResponse();

            // Return the file.
            return response()->download(
                storage_path('app/' . $directory . $request->input('file')),
                $request->input('file')
            );
        }

        return response()->json(['error' => 'File not found:' . $request->input('file')], 404);
    }
}
