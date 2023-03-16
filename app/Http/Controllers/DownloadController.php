<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Jobs\ExpireUserFiles;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user_id = $request->input('uid');
        $filename = $request->input('file');
        $expires = Carbon::createFromFormat('U', (int) $request->input('expires'));
        $date = $expires->clone()->addDays(-3)->format('Y-m-d');
        $source = "user/{$user_id}/{$date}/{$filename}";

        if (!Storage::exists($source)) {
            return response()->json(['error' => 'File not found:' . $request->input('file')], 404);
        }

        // Remove the file after it is downloaded.
        ExpireUserFiles::dispatchAfterResponse(
            $user_id,
            ["{$date}/{$filename}"]
        );

        // Return the file.
        return response()->download(
            storage_path("app/{$source}"),
            $request->input('file')
        );
    }
}
