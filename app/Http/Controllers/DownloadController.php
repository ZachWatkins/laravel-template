<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Carbon;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $expires = Carbon::createFromFormat('U', (int) $request->input('expires'));
        $date = $expires->clone()->addDays(-3)->format('Y-m-d');
        $user_id = $request->input('uid');
        $directory = "exports/{$date}/{$user_id}/";

        if (!Storage::exists($directory . $request->input('file'))) {

            return response()->json(['error' => 'File not found:' . $directory . ' ' . $request->input('file')], 404);

        }

        // Remove the file after it is downloaded.
        dispatch(function () use ($directory, $request) {
            Storage::delete($directory . $request->input('file'));
            if (empty(Storage::allFiles($directory))) {
                Storage::deleteDirectory($directory);
                if (empty(Storage::allFiles($directory . '../'))) {
                    Storage::deleteDirectory($directory . '../');
                }
            }
        })->afterResponse();

        // Return the file.
        return response()->download(
            storage_path('app/' . $directory . $request->input('file')),
            $request->input('file')
        );
    }
}
